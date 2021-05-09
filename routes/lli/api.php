<?php

use LocalheroPortal\Core\Feature\Citations\CitationsApiController;
use LocalheroPortal\Core\Feature\ProblemLocations\ProblemLocationsApiController;
use LocalheroPortal\LLI\Feature\CompanyManagement\CompanyApiController;
use LocalheroPortal\LLI\Feature\KeywordScraping\KeywordScrapingController;
use LocalheroPortal\LLI\Feature\Location\Image\LocationImageController;
use LocalheroPortal\LLI\Feature\Location\LocationApiController;
use LocalheroPortal\LLI\Http\Controllers\Api\CompanyLogController;
use LocalheroPortal\LLI\Http\Controllers\Api\LocationCompetitionStatisticsController;
use LocalheroPortal\LLI\Http\Controllers\Api\LocationRankQueryController;
use LocalheroPortal\LLI\Http\Controllers\Api\LocationSearchQueryStatisticsController;
use LocalheroPortal\LLI\Http\Controllers\Api\LocationUserActionStatisticsController;

Route::middleware(['can:manage-company'])->group(function () {
    Route::get('/companies/{company}/locations/{location}/statistics/actions', LocationUserActionStatisticsController::class);
    Route::get('/companies/{company}/locations/{location}/statistics/competition', LocationCompetitionStatisticsController::class);
    Route::get('/companies/{company}/locations/{location}/statistics/queries', LocationSearchQueryStatisticsController::class);
    Route::resources([
        'companies'                    => CompanyApiController::class,
        'companies.logs'               => CompanyLogController::class,
        'companies.locations'          => LocationApiController::class,
        'companies.locations.images'   => LocationImageController::class,
        'companies.locations.keywords' => LocationRankQueryController::class,
    ]);
});

Route::post('/location/{location}/scraping-results', [KeywordScrapingController::class, 'store']);
Route::post('/location/{location}/reportProblem', [ProblemLocationsApiController::class, 'reportProblem']);
Route::post('/location/{location}/solveProblem', [ProblemLocationsApiController::class, 'solveProblem']);
Route::post('/location/{location}/update/citations', [CitationsApiController::class, 'update'])->name('update.citations');

Route::get('/fetchCompanies', [CompanyApiController::class, 'index'])->name('fetch.companies');