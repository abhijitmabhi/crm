<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadSingleResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id'            => $this->id,
            'category'      => $this->category,
            'company_name'  => $this->company_name,
            'contact_name'  => $this->contact_name,
            'phone1'        => $this->phone1,
            'phone2'        => $this->phone2,
            'city'          => $this->city,
            'street'        => $this->street,
            'closed_until'  => Carbon::parse($this->closed_until)->format('j. F Y, h:i:s'),
            'self'          => $this->getApiLink(),
            'status'        => $this->status,
            'expert_status' => $this->expert_status,
            'zip'           => $this->zip,
            'updated_at'   => Carbon::parse($this->updated_at)->format('j. F Y, h:i:s'),
            'website'      => $this->website,
            'expert_id'    => $this->expert_id,
            'additional_contacts' => $this->additional_contacts,
            'important_note' => $this->important_note
        ];
        if ($this->expert) {
            $response['expert'] = $this->expert->name;
        }
        if ($this->phone2) {
            $response['phone2'] = $this->phone2;
        }
        if ($this->email) {
            $response['email'] = $this->email;
        }
        return $response;
    }
}
