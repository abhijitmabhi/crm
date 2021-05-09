<?php


namespace LocalheroPortal\Callcenter\UseCase;


use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Callcenter\Http\Controllers\AgentController;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Callcenter\UseCase\Exceptions\NoExpertException;
use LocalheroPortal\Callcenter\UseCase\Exceptions\NoLeadsException;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Models\User\User;

class GetNextFollowUpLeadUseCase
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
        $experts = $this->getAssignedExperts($agent)->shuffle();
        foreach ($experts as $expert) {
            if (empty($lead)) {
                $lead = $this->leadRepo
                    ->getFollowUpLeads($expert, [LeadState::APPOINTMENT, LeadState::NO_INTEREST])
                    ->first(AgentController::RELEVANT_LEAD_ATTRIBUTES);
            }
            if (empty($lead)) {
                $lead = $this->leadRepo
                    ->getFollowUpLeads($expert, [LeadState::NOT_REACHED])
                    ->callable()
                    ->first(AgentController::RELEVANT_LEAD_ATTRIBUTES);
            }
        }
        if ($lead == null) {
            throw new NoLeadsException();
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
     * @throws NoExpertException
     */
    private function getAssignedExperts($agent)
    {
        $experts = User::byCallagent($agent)->get();
        if ($experts->isEmpty()) {
            throw new NoExpertException();
        }
        return $experts;
    }

}