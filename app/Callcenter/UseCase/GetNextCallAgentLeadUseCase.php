<?php


namespace LocalheroPortal\Callcenter\UseCase;


use LocalheroPortal\Callcenter\Http\Controllers\AgentController;
use LocalheroPortal\Callcenter\UseCase\Exceptions\NoExpertException;
use LocalheroPortal\Callcenter\UseCase\Exceptions\NoLeadsException;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\User\User;

class GetNextCallAgentLeadUseCase
{

    private LeadRepository $leadRepo;

    public function __construct(LeadRepository $leadRepo)
    {
        $this->leadRepo = $leadRepo;
    }

    /**
     * @param $agent
     * @return Lead
     * @throws NoExpertException
     * @throws NoLeadsException
     */
    public function getNextLead($agent)
    {
        $lead = null;
        $recallLeads = $this->getRecallLeads($agent);
        if ($recallLeads) {
            $lead = $recallLeads->first();
        }
        $expert = $this->getAssignedExpertWithLeads($agent);
        if (empty($lead)) {
            $lead = $this->leadRepo
                ->getLeads($expert, [LeadState::COMPETITION_PROTECTION])
                ->whereDoesntHave('allCalendarEvents')
                ->first(AgentController::RELEVANT_LEAD_ATTRIBUTES);
        }
        if (empty($lead)) {
            $lead = $this->leadRepo
                ->getLeads($expert, [LeadState::OPEN])
                ->whereDoesntHave('allCalendarEvents')
                ->first(AgentController::RELEVANT_LEAD_ATTRIBUTES);
        }
        if (empty($lead)) {
            $lead = $this->leadRepo
                ->getLeads($expert, [LeadState::NOT_REACHED])
                ->whereDoesntHave('allCalendarEvents')
                ->first(AgentController::RELEVANT_LEAD_ATTRIBUTES);
        }
        if (empty($lead)) {
            $lead = $this->leadRepo
                ->getLeads($expert, [LeadState::NO_INTEREST])
                ->whereDoesntHave('allCalendarEvents')
                ->first(AgentController::RELEVANT_LEAD_ATTRIBUTES);
        }
        return $lead;
    }

    private function getRecallLeads($agent)
    {
        $leads = $this->leadRepo
            ->getAcuteRecalls($agent)
            ->get(AgentController::RELEVANT_LEAD_ATTRIBUTES);
        if ($leads->isEmpty()) {
            $leads = $this->leadRepo
                ->getRecalls($agent)
                ->get(AgentController::RELEVANT_LEAD_ATTRIBUTES);
        }
        return $leads;
    }

    /**
     * @param $agent
     * @return User
     * @throws NoExpertException
     * @throws NoLeadsException
     */
    private function getAssignedExpertWithLeads($agent)
    {
        $experts = User::byCallagent($agent)->get();
        if ($experts->isEmpty()) {
            throw new NoExpertException();
        }

        $leadRepo = $this->leadRepo;
        $experts_with_leads = $experts->filter(function ($expert) use ($leadRepo) {
            $leadsCount = $leadRepo->getLeads($expert, [LeadState::OPEN, LeadState::NOT_REACHED, LeadState::NO_INTEREST])
                ->whereDoesntHave('allCalendarEvents')->count();
            return $leadsCount !== 0;
        });
        if ($experts_with_leads->isEmpty()) {
            throw new NoLeadsException();
        }
        return $experts_with_leads->random();
    }

}