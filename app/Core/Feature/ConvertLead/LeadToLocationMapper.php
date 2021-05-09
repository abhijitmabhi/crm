<?php


namespace LocalheroPortal\Core\Feature\ConvertLead;


use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LLI\Location;

class LeadToLocationMapper
{

    public function mapLeadToLocation(Lead $lead): Location
    {
        $data = $lead->only(['city', 'email', 'coordinates', 'geo_coordinates', 'place_id']);
        $location = new Location($data);
        $location->name = $lead->company_name;
        $location->address = $lead->street;
        $location->postcode = $lead->zip;
        $location->phone = $lead->phone1;
        $location->mobilephone = $lead->phone2;
        if ($lead->website) {
            $location->website = $this->getBaseUrl($lead->website);
        }
        $location->lead_id = $lead->id;

        return $location;
    }

    public function getBaseUrl($url)
    {
        $urlComponents = parse_url($url);
        return $urlComponents['host'];
    }
}