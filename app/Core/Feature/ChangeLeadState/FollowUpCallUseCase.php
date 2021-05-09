<?php

namespace LocalheroPortal\Core\Feature\ChangeLeadState;

use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\LeadStateTag;

class FollowUpCallUseCase
{

    public Lead $lead;
    public int $state;

    public function __construct(Lead $lead, int $state)
    {
        $this->lead = $lead;
        $this->state = $state;
    }

    public function changeLeadState()
    {
        switch ($this->state) {
            case LeadState::APPOINTMENT:
                $this->lead->addState(LeadStateTag::FOLLOW_UP_ACCEPTED);
                break;
            case LeadState::NO_INTEREST:
            case LeadState::BLACKLIST:
                $this->lead->addState(LeadStateTag::FOLLOW_UP_REJECTED);
                break;
            default:
                break;
        }
        $this->lead->save();
    }

}