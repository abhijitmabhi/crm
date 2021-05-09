<?php

namespace LocalheroPortal\Core\Feature\ChangeLeadState;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LocalheroPortal\Models\LeadState;

class LeadStateChangeRequest extends FormRequest
{

    public function rules()
    {
        return [
            'state' => [
                'required',
                'numeric',
                Rule::in([
                    LeadState::NOT_REACHED,
                    LeadState::RECALL,
                    LeadState::NO_INTEREST,
                    LeadState::APPOINTMENT, 
                    LeadState::BLACKLIST,
                    LeadState::APPOINTMENT_NEEDED,
                    LeadState::COMPETITION_PROTECTION
                ])
            ],
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'comment' => 'nullable|string',
        ];
    }
}
