<?php


namespace LocalheroPortal\LLI\Feature\Location;


use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LLI\Location;

class LocationToLeadMapper
{

    public function mapLocationToLead(Location $location): Lead
    {
        $data = $location->only(['city', 'email', 'coordinates', 'geo_coordinates', 'place_id', 'website']);
        $lead = new Lead($data);
        $lead->company_name = $location->name;
        $lead->street = $location->address;
        $lead->zip = $location->postcode;
        $lead->phone1 = $location->phone;
        $lead->phone2 = $location->mobilephone;

        return $lead;
    }
}