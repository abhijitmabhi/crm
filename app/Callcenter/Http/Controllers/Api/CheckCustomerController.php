<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Callcenter\Http\Resources\CompanyListResource;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Util\UrlUtil;

class CheckCustomerController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'lead_id' => ['required', 'exists:leads,id', Rule::notIn([LeadState::INVALID, LeadState::CLOSED])]
        ]);

        $lead = Lead::find($request->lead_id);
//        $lead = Lead::find(1238);
        $similarLeads = $this->getSimilarCustomerLeads($lead);
        $customerCompanies = $similarLeads->map(function ($lead) {
            return $lead->company;
        });
        return Response::json(new CompanyListResource($customerCompanies));
    }

    public function getSimilarCustomerLeads($lead) {
        $leadQuery = Lead::whereStatus(LeadState::CLOSED)->where(function (Builder $query) use ($lead) {
            $leadQuery = $query
                ->orWhere('company_name', '=', $lead->company_name)
                ->orWhere('email', '=', $lead->email);
            if ($lead->website) {
                $domain = UrlUtil::getUrlDomain($lead->website);
                $leadQuery = $leadQuery
                    ->orWhere('website', 'like', '%' . $domain . '%');
            }
            if ($lead->phone1) {
                $leadQuery = $leadQuery
                    ->orWhere('phone1', '=', $lead->phone1)
                    ->orWhere('phone2', '=', $lead->phone1);
            }
            if ($lead->phone2) {
                $leadQuery = $leadQuery
                    ->orWhere('phone1', '=', $lead->phone2)
                    ->orWhere('phone2', '=', $lead->phone2);
            }
            return $leadQuery;
        });
        $leads = $leadQuery->get();
        return $leads;
    }
}
