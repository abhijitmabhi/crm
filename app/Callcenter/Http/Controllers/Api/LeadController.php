<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Callcenter\Http\Requests\UpdateLeadRequest;
use LocalheroPortal\Callcenter\Http\Resources\LeadCategoriesCollection;
use LocalheroPortal\Callcenter\Http\Resources\LeadSingleResource;
use LocalheroPortal\Callcenter\Notifications\AppointmentRejected;
use LocalheroPortal\Core\Facades\PhoneFormatter;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadExpertAcceptance;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class LeadController extends Controller
{

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return Response::json(['message' => 'Lead deleted']);
    }

    public function index(Request $request)
    {
        $parameters = $request->all();
        $para_keys = array_keys($parameters);
        $leads = Lead::withTrashed()->with('expert:id,first_name,last_name');
        if (in_array('contact_name', $para_keys) && !$request->contact_name) {
            $leads->whereNull('contact_name');
        }
        if (!empty($request->search)) {
            // Should be replaced once Elasticsearch is in place
            $leads->where(function ($query) use ($request) {
                $query->where('company_name', 'LIKE', "%$request->search%")
                    ->orWhere('contact_name', 'LIKE', "%$request->search%")
                    ->orWhere('phone1', 'LIKE', "%$request->search%")
                    ->orWhere('phone2', 'LIKE', "%$request->search%")
                    ->orWhere('street', 'LIKE', "%$request->search%")
                    ->orWhere('zip', 'LIKE', "%$request->search%")
                    ->orWhere('city', 'LIKE', "%$request->search%");
            });
        }
        if ($request->company_name) {
            $leads->where('company_name', 'LIKE', "%$request->company_name%");
        }
        if ($request->city) {
            $leads->whereCity($request->city);
        }
        if ($request->input('status') && is_array($request->input('status'))) {
            $leads->whereIn('status', $request->input('status'));
        }
        if (!empty($request->filter)) {
            $leads->whereStatus($request->filter);
        }
        if (!empty($request->agent)) {
            $leads->whereAgentId($request->agent);
        }
        if (!empty($request->expert)) {
            if ($request->expert === 'invalid') {
                $leads->whereDoesntHave('expert');
            } else {
                $leads->whereExpertId($request->expert);
            }
        }
        if (!empty($request->sort_by)) {
            $direction = $request->sort_direction === 'desc' ? 'DESC' : 'ASC';
            if ('expert' === $request->sort_by) {
                $leads
                    ->join('users', 'leads.expert_id', '=', 'users.id') // TODO: Expensive. Look for alternatives
                    ->orderByRaw("CASE WHEN users.deleted_at IS NULL THEN users.name ELSE 'zz' END $direction");
            } else {
                $leads->orderByRaw("COALESCE($request->sort_by, 'zz') $direction");
            }
        }
        if ($request->before) {
            $leads->where('closed_until', '<', Carbon::parse($request->before));
        }
        if ($request->after) {
            $leads->where('closed_until', '>', Carbon::parse($request->after));
        }
        $pagination = $request->per_page ?? 15;
        $leads = $leads->paginate($pagination);
        return LeadSingleResource::collection($leads);
    }

    public function show(Request $request, Lead $lead)
    {
        $isExpert = Auth::user()->hasRole(RoleType::EXPERT);
        $isSupervisor = Auth::user()->hasRole(RoleType::CALL_CENTER_SUPERVISOR);
        $leadExpertId = $lead->expert->id;
        if ($isExpert && !$isSupervisor && $leadExpertId != Auth::id()) {
            abort(403, "Access to lead of other expert is not allowed.");
        }
        if ($lead->hasMedia('leads')) {
            $lead['screenshot'] = $lead->getMedia('leads')->first()->getUrl();
        }

        if ('true' === $request->with_intervals) {
            $lead->intervals()->each(function ($interval) use (&$lead) {
                $lead['intervals'][$interval->id] = $interval->pivot->time_spent;
            });
        }
        return new LeadSingleResource($lead);
    }

    public function update(Lead $lead, UpdateLeadRequest $request)
    {
        $lead->fill($request->validated());
        $lead->save();
        return Response::json(['message' => 'lead corrected']);
    }

    public function updateExpertStatus(Lead $lead, Request $request)
    {
        $lead->expert_status = $request->expert_status;
        $lead->save();

        if ($lead->expert_status == LeadExpertAcceptance::REJECTED) {
            $toNotify = User::byRole(RoleType::CALL_CENTER_SUPERVISOR)->get();
            $expert = $lead->expert;
            $toNotify = $toNotify->merge($expert->callagents->map(function ($pivot) {
                return $pivot->agent;
            }));
            Notification::send($toNotify, new AppointmentRejected(
                $expert,
                $lead,
                $request->reasoning ?? 'Der Experte hat keine Begründung geliefert'
            ));
        }

        return Response::json(['message' => 'Status gespeichert.']);
    }

    public function store(Request $request)
    {
        $result = $request->place;
        $location = $result['geometry']["location"];
        if (!isset($result['international_phone_number'])) {
            abort(422, 'Keine Telefonnummer gefunden');
        }
        $lead = new Lead([
            'company_name' => $result['name'],
            'place_id' => $result['place_id'],
            'phone1' => $result['international_phone_number'],
            'website' => $result['website'] ?? null,
            'coordinates' => [
                'lat' => $location['lat'],
                'lng' => $location['lng']
            ],
            'category' => $this->assumeType($result['types']),
            'expert_id' => $request->expert
        ]);
        $address = [];
        foreach ($result['address_components'] as $component) {
            if (in_array('locality', $component['types'])) {
                $lead->city = $component['long_name'];
            } elseif (in_array('postal_code', $component['types'])) {
                $lead->zip = $component['long_name'];
            } elseif (in_array('route', $component['types'])) {
                $address['street'] = $component['long_name'];
            } elseif (in_array('street_number', $component['types'])) {
                $address['number'] = $component['long_name'];
            }
        }
        $lead->street = explode(',', $result['formatted_address'])[0];
        $lead->phone1 = PhoneFormatter::formatInternational($lead->phone1);
        try {
            $lead->save();
            Comment::create([
                'reason' => CommentReason::CREATED,
                'body' => "Wurde aus googleMaps importiert.",
                'user_id' => Auth::user()->id,
                'commentable_type' => 'lead',
                'commentable_id' => $lead->id,
                'date' => now('Europe/Berlin'),
            ]);
        } catch (QueryException $e) {
            abort(422, 'Lead wurde bereits erfasst');
        }
    }

    protected function assumeType(array $types)
    {
        $lookup = json_decode(file_get_contents(base_path('resources/google/relevant_categories.json')), true);
        foreach ($types as $type) {
            if (in_array($type, array_keys($lookup))) {
                return $lookup[$type];
            }
        }
    }

    public function categories(Request $request)
    {
        $categories = Lead::query()->select('category')->groupBy('category');
        if ($request->search) {
            $categories->where('category', 'like', "%$request->search%");
        }
        return new LeadCategoriesCollection($categories->paginate());
    }

    public function getLastAppointment(Lead $lead)
    {
        return $lead->calendarEvents->last();
    }

    public function changeExpert(Lead $lead, User $user): JsonResponse
    {
        if (!$user->hasRole(RoleType::EXPERT)) {
            return Response::json('Benutzer ist kein SAM.', 400);
        }
        $lead->expert_id = $user->id;
        $lead->save();
        return Response::json('Lead erfolgreich übergeben.');
    }

    public function revertBlacklist(Lead $lead): JsonResponse
    {
        if ($lead->status != LeadState::BLACKLIST) {
            return Response::json('Lead muss Status Blacklist haben.', 400);
        }
        $lead->status = LeadState::NO_INTEREST;
        $lead->save();
        return Response::json('Lead erfolgreich geändert.');
    }
}
