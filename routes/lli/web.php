<?php

use LocalheroPortal\LLI\Feature\GoogleAuth\CompanyGoogleAuthController;
use LocalheroPortal\LLI\Feature\KeywordScraping\KeywordScrapingController;
use LocalheroPortal\LLI\Feature\CompanyManagement\CompanyWebController;
use LocalheroPortal\LLI\Feature\DetailedUserActions\DetailedUserActionsController;
use LocalheroPortal\LLI\Feature\Location\LocationWebController;
use LocalheroPortal\LLI\Feature\Support\SupportContactController;
use LocalheroPortal\LLI\Feature\Settings\SettingsController;
use LocalheroPortal\LLI\Http\Controllers\CompanyLogController;
use LocalheroPortal\LLI\Http\Controllers\LocationStatisticsController;

Route::middleware(['can:manage-company'])->group(function () {
    Route::get('/companies/{company}/locations/statistics', [LocationStatisticsController::class, 'index'])->name('companies.statistics');
    Route::get('/companies/{company}/locations/{location}/statistics', [LocationStatisticsController::class, 'show']);
    Route::get('/companies/{company}/locations/{location}/statistics/edit', [LocationStatisticsController::class, 'edit']);

    Route::resources([
        'companies'           => CompanyWebController::class,
        'companies.locations' => LocationWebController::class,
        'companies.logs'      => CompanyLogController::class,
    ]);
});

Route::get('/support', [SupportContactController::class, 'getView'])->name('support');
Route::post('/support-request', [SupportContactController::class, 'sendSupportRequest'])->name('support-request');
Route::get('/settings', [SettingsController::class, 'getView'])->name('settings');

Route::get('/companies/', [CompanyWebController::class, 'index'])->name('companies.index');

Route::get('/location/keyword/scraping', [KeywordScrapingController::class, 'index'])->name('lli-data-scraping');

Route::get('/companies/{company}/redirect', [CompanyGoogleAuthController::class, 'redirect'])->name('company.google.auth');
Route::get('/googleoauth', [CompanyGoogleAuthController::class, 'oauth2callback'])->name('company.google.callback');

Route::get('/detailedUserActions/{company}', [DetailedUserActionsController::class, 'show']);
