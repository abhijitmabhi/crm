<?php

namespace LocalheroPortal\Core\Feature\CreateLead;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Callcenter\Http\Resources\LeadSingleResource;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\CalendarEventType;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadExpertAcceptance;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\User;
use function Sentry\captureException;

class CreateLeadController extends Controller
{

    public function __invoke(CreateLeadRequest $request, User $user)
    {
        $agent = Auth::user();
        $lead = new Lead();
        $lead->fill($request->except(['appointment_end', 'appointment_comment']));
        // Leads created by experts will be blocked
        if ($user->id === $agent->id) {
            $lead->blocked = true;
        }
        $lead->phone1 = PhoneUtil::formatPhoneNumber($lead->phone1);
        DB::beginTransaction();
        try {
            $lead->save();

            if ($request->closed_until) {
                $lead->closed_until = Carbon::create($request->closed_until);
                $event_end = $request->appointment_end
                    ? Carbon::parse($request->appointment_end)
                    : Carbon::parse($request->closed_until)->addMinutes(90);
                $lead->calendarEvents()->save(new CalendarEvent([
                    'body' => $request->appointment_comment ?? "Wurde von {$this->roleDescriptor($agent->roles)}".$agent->name." angelegt.",
                    'type' => CalendarEventType::CALLCENTER_APPOINTMENT,
                    'event_begin' => $lead->closed_until,
                    'event_end' => $event_end,
                ]));
                $lead->status = LeadState::APPOINTMENT;
                $lead->expert_status = LeadExpertAcceptance::ACCEPTED;
            } else {
                $lead->status = LeadState::OPEN;
            }
            if (isset($request->agent)) {
                $lead->agent_id = $request->agent;
            }

            $user->leads()->save($lead);
            $this->addCreatedComment($lead, $agent);
            if ($request->closed_until) {
                $this->addAppointmentComment($lead, $agent);
            }
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::json(['errors' => ['Speichern fehlgeschlagen. Support wurde kontaktiert.']], 500);
        }

        DB::commit();
        return Response::json(['message' => 'Lead saved', 'lead' => new LeadSingleResource($lead)]);
    }

    protected function addCreatedComment(Lead $lead, $agent)
    {
        $lead->comments()->save(new Comment([
            'body' => "Wurde von {$this->roleDescriptor($agent->roles)}".$agent->name." angelegt.",
            'user_id' => $agent->id,
            'commentable_type' => 'lead',
            'commentable_id' => $lead->id,
            'reason' => CommentReason::CREATED,
            'date' => now('Europe/Berlin'),
        ]));
    }

    protected function addAppointmentComment(Lead $lead, $agent)
    {
        $lead->comments()->save(new Comment([
            'body' => "Termin wurde von {$this->roleDescriptor($agent->roles)}".$agent->name." vereinbart.",
            'user_id' => $agent->id,
            'commentable_type' => 'lead',
            'commentable_id' => $lead->id,
            'reason' => CommentReason::APPOINTMENT,
            'date' => now('Europe/Berlin')->addMinute(),
        ]));
    }

    protected function roleDescriptor($userRoles)
    {
        if ($userRoles->has('expert')) {
            return 'Experte ';
        } elseif ($userRoles->has('callcenter-agent')) {
            return 'Call Agent ';
        } else {
            return '';
        }
    }
}
