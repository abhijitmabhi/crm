<?php

namespace LocalheroPortal\LLI\Http\Controllers;

use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;

class LocationStatisticsController extends Controller
{
    public function index(Request $request, Company $company)
    {
        $locations = $company->locations()->get(['id', 'name']);
        return view('lli.location.CustomerDashboardView', compact('company', 'locations'));
    }

    public function show(Request $request, Company $company, Location $location)
    {
        return view('lli.location.CustomerDashboardView', [
            'company'   => $company,
            'locations' => collect([$location]),
        ]);
    }

    public function edit(Request $request, Company $company, Location $location)
    {
        $locations = $company->locations()->get(['id', 'name']);
        return view('lli.location.EditLocationView', compact('company', 'location'));
    }
}
