<?php


namespace LocalheroPortal\Core\Feature\Search;

use Illuminate\Http\JsonResponse;
use LocalheroPortal\Core\Http\Controllers\Controller;

class SearchApiController extends Controller
{

    public function search(SearchRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $searchTerm = $requestData["searchTerm"];
        $phone = $requestData["phone"] ?? '';
        $limit = $requestData["limit"] ?? 5;

        $searchService = new SearchService();
        $result = $searchService->searchWithSql($searchTerm, $phone, $limit);
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function searchLocation(SearchRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $searchTerm = $requestData["searchTerm"];
        $phone = $requestData["phone"] ?? '';
        $limit = $requestData["limit"] ?? 5;

        $searchService = new SearchService();
        $result = $searchService->searchLocations($searchTerm, $phone, $limit);
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function searchCompany(SearchRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $searchTerm = $requestData["searchTerm"];
        $phone = $requestData["phone"] ?? '';
        $email = $requestData["email"] ?? '';
        $limit = $requestData["limit"] ?? 5;

        $searchService = new SearchService();
        $result = $searchService->searchCompanies($searchTerm, $phone, $email, $limit);
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function searchLead(SearchRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $searchTerm = $requestData["searchTerm"];
        $phone = $requestData["phone"] ?? '';
        $limit = $requestData["limit"] ?? 5;

        $searchService = new SearchService();
        $result = $searchService->searchLeads($searchTerm, $phone, $limit);
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
