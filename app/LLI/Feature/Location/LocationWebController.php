<?php

namespace LocalheroPortal\LLI\Feature\Location;

use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\CitationSourceRepository;
use LocalheroPortal\LLI\Jobs\ImportLocations;
use LocalheroPortal\Models\GoogleBusinessCategory;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;

class LocationWebController extends Controller
{

    public function create(Company $company)
    {
        $allCategories = GoogleBusinessCategory::query()->groupBy('name')->pluck('name');
        $citationSourceRepo = new CitationSourceRepository();
        $allCitationCategories = $citationSourceRepo->getAllCategories();
        return view('lli.location.CreateLocationView', [
            'company' => $company,
            'allCategories' => $allCategories,
            'allCitationCategories' => $allCitationCategories
        ]);
    }

    public function index(Company $company)
    {
        ImportLocations::dispatch($company);
        return view('lli.location.index', ['company' => $company]);
    }

    public function show(Company $company, Location $location)
    {
        if (!$location->coordinates) {
            $location->coordinates = array('lat' => '', 'lng' => '');
        }

        $citationSourceRepo = new CitationSourceRepository();
        $allCitationCategories = $citationSourceRepo->getAllCategories();
        $mainCategory = $location->mainCategories()->value('name');
        $additionalCategories = $location->additionalCategories()->pluck('name')->toArray();
        $allCategories = GoogleBusinessCategory::query()->groupBy('name')->pluck('name');
        $keywordsActive = $location->rankQueries()->pluck('keyword')->toArray();
        $keywordsDeleted = $location->rankQueries()->onlyTrashed()->pluck('keyword')->toArray();
        $selectedCitationCategories = array_unique($location->activeCitations()->groupBy('category')->pluck('category')->toArray());
        $location->setAttribute('keywordsActive', $keywordsActive);
        $location->setAttribute('keywordsDeleted', $keywordsDeleted);
        $location->setAttribute('mainCategory', $mainCategory);
        $location->setAttribute('additionalCategories', $additionalCategories);
        $location->setAttribute('selectedCitationCategories', $selectedCitationCategories);

        return view('lli.location.EditLocationView', [
            'location' => $location,
            'company' => $company,
            'allCategories' => $allCategories,
            'allCitationCategories' => $allCitationCategories
        ]);
    }

}
