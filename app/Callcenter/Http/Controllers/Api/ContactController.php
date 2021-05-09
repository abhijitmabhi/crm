<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Http\Request;
use LocalheroPortal\Callcenter\Http\Resources\ContactCollection;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;

class ContactController extends Controller
{
    public function __invoke(Request $request)
    {
        if (isset($request->searchterm)) {
            $leads = Lead::search($request->searchterm);
            $companies = Company::search($request->searchterm);
            return new ContactCollection($leads->get()->merge($companies->get()));
        }
    }
}
