<?php

namespace LocalheroPortal\Callcenter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use LocalheroPortal\Callcenter\UseCase\Exceptions\NoExpertException;
use LocalheroPortal\Callcenter\UseCase\Exceptions\NoLeadsException;
use LocalheroPortal\Callcenter\UseCase\GetNextCallAgentLeadUseCase;
use LocalheroPortal\Callcenter\UseCase\GetNextFollowUpLeadUseCase;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\RoleType;

class AgentController extends Controller
{
    // Has great impact on query-speed => USE
    public const RELEVANT_LEAD_ATTRIBUTES = [
        'id',
        'company_name',
        'street',
        'zip',
        'city',
        'title',
        'contact_name',
        'additional_contacts',
        'phone1',
        'phone2',
        'email',
        'website',
        'category',
        'sub_category',
        'expert_id',
        'agent_id',
        'important_note',
    ];

    /**
     * @param  Lead  $lead
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return back();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $lead = Cache::get(Auth::id().'lead');

        if ($lead && $lead->expert()->withTrashed()->whereNotNull('deleted_at')->count()) {
            Cache::forget(Auth::id().'lead');
            $lead = null;
        }

        $leadRepo = new LeadRepository();
        $isFollowUpAgent = Auth::user()->hasRole(RoleType::FOLLOW_UP_AGENT);
        if (empty($lead)) {
            if ($isFollowUpAgent) {
                $useCase = new GetNextFollowUpLeadUseCase($leadRepo);
            } else {
                $useCase = new GetNextCallAgentLeadUseCase($leadRepo);
            }
            try {
                $lead = $useCase->getNextLead(Auth::user());
            } catch (NoExpertException $e) {
                return view('callcenter.noExpert');
            } catch (NoLeadsException $e) {
                return view('callcenter.done');
            }
            Cache::rememberForever(Auth::id().'lead', function () use ($lead) {
                return $lead;
            });
        }

        $expert = $lead->expert;
        if ($isFollowUpAgent) {
            $lead->blocked = true;
        }
        $lead->lock(Auth::id());
        $leadsCount = $leadRepo->getLeads($expert,
            [LeadState::OPEN, LeadState::NOT_REACHED, LeadState::NO_INTEREST])->count();
        $appointmentsCount = Lead::forUser($expert)->stateAppointment()->count();
        $appointment = $lead->calendarEvents->last();

        return view('callcenter.index',
            [
                'expert' => $expert,
                'leadsCount' => $leadsCount,
                'lead' => $lead,
                'appointmentsCount' => $appointmentsCount,
                'appointmentId' => $appointment ? $appointment->id : -1,
                'recalls' => $recalls ?? [],
                'timer' => true
            ]);
    }

    public function recalls()
    {
        $recalls = Lead::forUser(Auth::user())->get();
        return view('callcenter.recalls', ['recalls' => $recalls]);
    }

    /**
     * @param  Lead  $lead
     */
    public function show(Lead $lead, Request $request)
    {
        $isExpert = Auth::user()->hasRole(RoleType::EXPERT);
        $isSupervisor = Auth::user()->hasRole(RoleType::CALL_CENTER_SUPERVISOR);
        $leadExpertId = $lead->expert->id;
        if ($isExpert && !$isSupervisor && $leadExpertId != Auth::id()) {
            abort(403, "Access to lead of other expert is not allowed.");
        }
        if ($request->has('timer')) {
            $timer = boolval($request->query('timer'));
        } else {
            $timer = true;
        }
        $leadExpert = $lead->expert;
        $appointmentsCount = Lead::forUser($leadExpert)->stateAppointment()->count();
        $appointment = $lead->calendarEvents->last();
        $leadRepo = new LeadRepository();
        $leadsCount = $leadRepo->getLeads($leadExpert, [LeadState::OPEN, LeadState::NOT_REACHED, LeadState::NO_INTEREST])
            ->whereDoesntHave('allCalendarEvents')->count();
        $viewData = [
            'lead' => $lead,
            'leadsCount' => $leadsCount,
            'expert' => $leadExpert,
            'appointmentsCount' => $appointmentsCount,
            'timer' => $timer,
            'appointmentId' => $appointment ? $appointment->id : -1
        ];
        return view('callcenter.index', $viewData);
    }
}
