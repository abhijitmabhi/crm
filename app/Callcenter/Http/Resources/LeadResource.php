<?php

namespace LocalheroPortal\Callcenter\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\Lead;

/**
 * @mixin Lead
 */
class LeadResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($lead) use (&$response) {
            $data = [
                'id'           => $lead->id,
                'company_name' => $lead->company_name,
                'contact_name' => $lead->contact_name,
                'city'         => $lead->city,
                'closed_until' => Carbon::parse($lead->closed_until)->format('j. F Y, h:i:s'),
                'self'         => $lead->getApiLink(),
                'status'       => $lead->status,
                'updated_at'   => Carbon::parse($lead->updated_at)->format('j. F Y, h:i:s'),
                'website'      => $lead->website,
                'expert_id'    => $lead->expert_id,
            ];
            // LEAD WITHOUT EXPERT = BAD!!!
            if ($lead->expert) {
                $data['expert'] = $lead->expert->name;
            }
            if ($lead->agent_id) {
                $data['agent'] = $lead->agent->name;
            }
            return $data;
        });
    }
}
