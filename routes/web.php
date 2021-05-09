<?php

use Illuminate\Support\Facades\Route;
use LocalheroPortal\Callcenter\Http\Controllers\LeadController;
use LocalheroPortal\Core\Feature\Citations\CitationsWebController;
use LocalheroPortal\Core\Feature\Customer\CustomerWebController;
use LocalheroPortal\Core\Feature\CustomerProgress\CustomerProgressWebController;
use LocalheroPortal\Core\Feature\ExpertSettings\ExpertAreaController;
use LocalheroPortal\Core\Feature\ExpertSettings\ExpertCategoryController;
use LocalheroPortal\Core\Feature\ExpertSettings\ExpertLocationController;
use LocalheroPortal\Core\Feature\ExportCalendar\ExportCalendarController;
use LocalheroPortal\Core\Feature\ImportCustomers\ImportCustomersController;
use LocalheroPortal\Core\Feature\ImportLeads\ImportLeadsController;
use LocalheroPortal\Core\Feature\Migration\MigrationController;
use LocalheroPortal\Core\Feature\ProblemLocations\ProblemLocationsController;
use LocalheroPortal\Core\Feature\Search\SearchWebController;
use LocalheroPortal\Core\Http\Controllers\Auth\ForgotPasswordController;
use LocalheroPortal\Core\Http\Controllers\Auth\LoginController;
use LocalheroPortal\Core\Http\Controllers\Auth\ResetPasswordController;
use LocalheroPortal\Core\Http\Controllers\CalendarController;
use LocalheroPortal\Core\Http\Controllers\ChangelogController;
use LocalheroPortal\Core\Http\Controllers\HomeController;
use LocalheroPortal\Core\Http\Controllers\LeadCategoryStatsController;
use LocalheroPortal\Core\Http\Controllers\MapController;
use LocalheroPortal\Core\Http\Controllers\NotificationController;
use LocalheroPortal\Core\Http\Controllers\OfflineController;
use LocalheroPortal\Core\Http\Controllers\RoleController;
use LocalheroPortal\Core\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class);
    Route::get('/map', MapController::class)->name('map')->middleware('can:view-map');
    Route::get('/calendar', CalendarController::class)->name('calendar');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/leads/no-contact', [LeadController::class, 'showWithoutContactName'])->name('lead.no-contact');
    Route::resources([
        'users' => UserController::class,
        'roles' => RoleController::class,

    ]);
    Route::get('/expert/location', [ExpertLocationController::class, 'getExpertLocation'])->name('expert.location');
    Route::post('/expert/location', [ExpertLocationController::class, 'postExpertLocation'])->name('expert.location.save');

    Route::get('/experts/area', [ExpertAreaController::class, 'getExpertArea'])->name('GetExpertArea');
    Route::post('/experts/area', [ExpertAreaController::class, 'saveExpertArea'])->name('SaveExpertArea');

    Route::get('/leads/by_number/{phone_number}', [LeadController::class, 'show']);
});

Route::get('/offline', OfflineController::class)->name('offline');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/calendar/{expert_cal}/', ExportCalendarController::class)->name('calendar.export');
Route::get('/changelogs', ChangelogController::class)->name('changelog');

Route::middleware(['can:manage-company'])->group(function () {
    Route::get('/companies/batch-import', [ImportCustomersController::class, 'getImportCustomersForm'])->name('companies.import');
    Route::post('company/import', [ImportCustomersController::class, 'postImportCustomers']);
    Route::get('/customer/check', [CustomerWebController::class, 'checkCustomerView'])->name('customer.check');

    Route::get('/locations/{location}/citations', [CitationsWebController::class, 'show'])->name('locations.citations.show');
    Route::get('/location/unfinished', [CustomerProgressWebController::class, 'getUnfinishedLocationView'])->name('location.unfinished');
});

Route::prefix('migration')->name('migration.')->middleware('can:manage-company')->group(function () {
    Route::get('lead', [MigrationController::class, 'startLeadMigration']);
    Route::get('location', [MigrationController::class, 'startLocationMigration']);
});

Route::prefix('admin')->name('admin.')->middleware('can:manage-company')->group(function () {
    Route::get('lead/import/blacklist', [ImportLeadsController::class, 'getImportBlacklistLeadsView']);
    Route::post('lead/import/blacklist', [ImportLeadsController::class, 'importBlacklistLeads']);
});

Route::prefix('admin')->name('admin.')->middleware('can:supervise-callcenter')->group(function () {
    Route::get('lead/import', [ImportLeadsController::class, 'getImportLeadsView'])->name('getImportLeadsView');
    Route::post('lead/import', [ImportLeadsController::class, 'importLeads'])->name('importLeads');
});

Route::get('/lead/category', [LeadCategoryStatsController::class, 'getLeadCategoryStats'])->middleware('can:supervise-callcenter')->name('lead.category');

Route::get('/companies/problems', [ProblemLocationsController::class, 'showAllLocationsWithProblems'])->name('companies.problems');

Route::get('/search', [SearchWebController::class, 'search'])->name('search');

Route::prefix('expert/category')->name('expert.category.')->middleware('can:supervise-callcenter')->group(function () {
    Route::get('/view', [ExpertCategoryController::class, 'getExpertCategoryView'])->name('view');
    Route::post('/prioritize', [ExpertCategoryController::class, 'prioritizeCategories'])->name('prioritize');
    Route::post('/exclude', [ExpertCategoryController::class, 'excludeCategories'])->name('exclude');
});


