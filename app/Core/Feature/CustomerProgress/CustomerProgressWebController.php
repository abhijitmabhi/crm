<?php

namespace LocalheroPortal\Core\Feature\CustomerProgress;


use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\CompanyRepository;
use LocalheroPortal\Core\Repository\LocationRepository;

class CustomerProgressWebController extends Controller
{

    public function getUnfinishedLocationView()
    {
        $companyRepo = new CompanyRepository();
        $companiesNoLocations = $companyRepo->getWithoutLocations();
        $locationRepo = new LocationRepository();
        $unfinishedLocations = $locationRepo->getUnfinished();
        return view('lli.location.CustomerProgressView', ['unfinishedLocations' => $unfinishedLocations, 'companiesNoLocations' => $companiesNoLocations]);
    }

}
