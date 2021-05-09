<?php

namespace LocalheroPortal\Core\Feature\Migration;

use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Callcenter\Jobs\FetchLeadCoordinates;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;

class MigrationController extends Controller
{

    public function startCompanyWithNoLocationsMigration() {
        $companies = Company::all();
        $companies = $companies->filter(function($c) {
            return count($c->locations()->get()) == 0;
        });
        foreach ($companies as $company) {
            $location = new Location();
            $location->name = $company->name;
            $location->company_id = $company->id;
            $location->address = $company->street;
            $location->postcode = $company->zip;
            $location->city = $company->city;
            $location->country = 'DE';
            $location->phone = $company->phone;
            $location->mobilephone = $company->phone_mobile;
            $location->email = $company->email;
            $location->website = $company->url;
            $location->save();
        }
        return response()->json('Done.');
    }

    public function startCustomerMigration()
    {
        $companies = Company::whereLeadId(null)->get();
        foreach ($companies as $company)
        {
            $leadQuery = Lead::whereCompanyName($company->name)
                ->orWhere('email', '=', $company->email);
            if ($company->url) {
                $leadQuery = $leadQuery
                    ->orWhere('website', 'like', '%' . $company->url . '%');
            }
            if ($company->phone) {
                $leadQuery = $leadQuery
                    ->orWhere('phone1', '=', $company->phone)
                    ->orWhere('phone2', '=', $company->phone);
            }
            foreach ($company->locations() as $location) {
                $leadQuery = $leadQuery
                    ->orWhere('name', '=', $location->name)
                    ->orWhere('email', '=', $location->email);
            }
            $lead = $leadQuery->first();
            if ($lead) {
                $company->lead_id = $lead->id;
                $lead->status = LeadState::CLOSED;
                $company->save();
            }
        }
        return response()->json('Done.');
    }

    public function startLocationMigration()
    {
        $locations = Location::whereWebsite(null)->get();
        foreach ($locations as $location) {
            $company = $location->company;
            $location->website = $company->url;
            $location->save();
        }
        return response()->json('Done.');
    }

    public function startLeadMigration()
    {
        $leads = Lead::withTrashed()->whereNull('coordinates')->get();
        foreach ($leads as $lead) {
            FetchLeadCoordinates::dispatch($lead);
        }

        // migration using job
//        $leadCount = Lead::count();
//        $step = 100;
//        for ($i = 0; $i < intval($leadCount / $step) + 1; $i++) {
//            $minId = $i * $step;
//            $maxId = ($i + 1) * $step;
//            MigrateLeadDuplicateJob::dispatch($minId, $maxId);
//        }

        // migration to assign lead to location
//        $leadRepo = new LeadRepository();
//        $locations = Location::all();
//        $deletableStatuses = [LeadStates::NO_INTEREST, LeadStates::NOT_REACHED, LeadStates::OPEN, LeadStates::RECALL, LeadStates::TOO_MANY_TRIES, LeadStates::INVALID];
//        foreach ($locations as $location) {
//            $similarLeads = $leadRepo->getAllSimilarLeads($location->email, $location->website, $location->phone, $location->mobilephone);
//            if (count($similarLeads) > 1) {
//                $deletableLeads = $similarLeads->filter(function ($similarLead) use ($deletableStatuses) {
//                    return in_array($similarLead->status, $deletableStatuses);
//                });
//                if (count($deletableLeads) == count($similarLeads)) {
//                    // should never happen
//                    $deletableLeads = $deletableLeads->reverse();
//                }
//                foreach ($deletableLeads as $deletableLead) {
//                    $test = $deletableLead;
////                    $deletableLead->delete();
//                }
//            }
//        }
        return response()->json('Done.');
    }
}
