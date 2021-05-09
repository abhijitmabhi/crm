<?php

namespace LocalheroPortal\LLI\Feature\DetailedUserActions;

use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;


class DetailedUserActionsController extends Controller
{

    public function show(Company $company)
    {
        return view('lli.company.detailedUserActions', ['company' => $company]);
    }

}
