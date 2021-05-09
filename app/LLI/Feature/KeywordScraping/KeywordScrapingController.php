<?php

namespace LocalheroPortal\LLI\Feature\KeywordScraping;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\LLI\Feature\Location\LocationValidationUseCase;
use LocalheroPortal\Models\LLI\KeywordUsageResult;
use LocalheroPortal\Models\LLI\LocationState;


class KeywordScrapingController extends Controller {

    public function index()
    {
        $locationRepo = new LocationRepository();
        $locationNoScrapingEntry = $locationRepo->getLocationNoScrapingResults();
        if(!is_null($locationNoScrapingEntry)) {
            $location = $locationNoScrapingEntry;
        } else {
            $location = $locationRepo->getLocationWithScrapingResults();
        }


        return view('lli.scraping.KeywordScrapingView', ['location' => $location]);
    }

    public function store(Request $request, $locationId)
    {
        //Validation Logic is implemented within the Parser class
        $termsParser = new KeywordScrapingParser($request->unparsedSearchTerms);
        $parsedTerms = $termsParser->getJsonParsedTerms();
        $scrapingResultEntry = new KeywordUsageResult([
            'location_id' => $locationId,
            'results' => $parsedTerms
        ]);
        try {
            $scrapingResultEntry->save();

            $repo = new LocationRepository();
            $location = $repo->getById($locationId);
            $useCase = new LocationValidationUseCase($location);
            $useCase->onStatisticsChanged();
            $location->save();
        } catch (QueryException $e) {
            abort(400, 'Saving the terms did not work!');
        }
    }
}