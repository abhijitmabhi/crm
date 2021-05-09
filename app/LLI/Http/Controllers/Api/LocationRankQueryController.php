<?php

namespace LocalheroPortal\LLI\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LocationRepository;
use LocalheroPortal\LLI\Http\Requests\SeoRankQueryFormRequest;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\RankQuery;

class LocationRankQueryController extends Controller
{
    public function index(Request $request, Company $company, Location $location)
    {
        return response()->json($location->rankQueries);
    }

    public function show(Request $request, Company $company, Location $location, string $queryId)
    {
        $seoRankQuery = RankQuery::findOrFail($queryId);
        return response()->json($seoRankQuery);
    }

    /**
     * @param  SeoRankQueryFormRequest  $request
     * @param  Company  $company
     * @param  string  $locationId
     * @return JsonResponse
     * @throws Exception
     */
    public function store(SeoRankQueryFormRequest $request, Company $company, string $locationId)
    {
        $locationRepo = new LocationRepository();
        $location = $locationRepo->getById($locationId);
        if ($location == null) {
            throw new Exception('Location not found.', 404);
        }
        $seoRankQuery = null;
        if ($location->seoRankQueries()->whereKeyword($request->keyword)->count()) {
            return response()->json(['allready attached']);
        }
        DB::transaction(function () use ($location, $request, &$seoRankQuery) {
            $seoRankQuery = RankQuery::firstOrCreate($request->all(['keyword']));
            $location->seoRankQueries()->save($seoRankQuery);
        });
        return response()->json($seoRankQuery, 201);
    }

    public function update(Request $request, Company $company, Location $location, string $seoRankQueryId)
    {
        $seoRankQuery = RankQuery::findOrFail($seoRankQueryId);
        DB::transaction(function () use ($request, $seoRankQuery) {
            $seoRankQuery->update($request->all($seoRankQuery->getFillable()));
            $seoRankQuery->save();
        });
        return response()->json($seoRankQuery);
    }

    public function destroy(Request $request, Company $company, Location $location, string $seoRankQueryId)
    {
        $success = null;
        DB::transaction(function () use ($seoRankQueryId, $location, &$success) {
            $success = $location->rankQueries()->detach([$seoRankQueryId]);
        });
        return response()->json(['resource_detached' => !!$success]);
    }
}
