<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\Lead;

class ExpertLeadListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'company_name'     => $this->company_name,
            'contact_name'     => $this->contact_name,
            'phone'            => $this->getPhoneNrsArray(),
            'city'             => $this->city,
            'street'           => $this->street,
            'closed_until'     => (string) $this->closed_until,
            'expert'           => $this->expert->name,
            'self'             => $this->getApiLink(),
            'status'           => $this->status,
            'expert_status'    => $this->expert_status,
            'zip'              => $this->zip,
            'status'           => $this->status,
            'created_at'       => Carbon::make($this->created_at)->format('d.m.Y'),
            'private'          => !!$this->blocked,
            'next_appointment' => $this->when($this->status === LeadState::APPOINTMENT, function () {
                return Carbon::make($this->calendarEvents->last()->event_begin)->format('d.m.Y');
            }),
            'last_interaction' => $this->when($this->comments->last(), function () {
                return Carbon::make($this->comments->last()->created_at)->format('d.m.Y');
            }),
        ];
    }
}
