<?php

use LocalheroPortal\Callcenter\Http\Controllers\Api\ExpertController;
use LocalheroPortal\Core\Feature\ChangeLeadState\LeadStateChangeApiController;
use LocalheroPortal\Core\Feature\ConvertLead\LeadCustomerConversionController;
use LocalheroPortal\Core\Feature\CreateLead\CreateLeadController;
use LocalheroPortal\Core\Feature\Search\SearchApiController;
use LocalheroPortal\Core\Feature\CustomerProgress\CustomerProgressApiController;
use LocalheroPortal\Core\Http\Controllers\Api\CalendarEventApiController;
use LocalheroPortal\Core\Http\Controllers\Api\CategoryController;
use LocalheroPortal\Core\Http\Controllers\Api\MapController;
use LocalheroPortal\Core\Http\Controllers\Api\UserCalendarController;
use LocalheroPortal\Core\Http\Controllers\Api\UserCommentController;
use LocalheroPortal\Core\Http\Controllers\Api\UserController;
use LocalheroPortal\Core\Http\Controllers\Api\UserFlagController;
use LocalheroPortal\Core\Http\Controllers\Api\UserLeadController;
use LocalheroPortal\Core\Http\Controllers\Api\UserNotificationController;


Route::post('users/{user}/appointments/{appointment}/restore', [UserCalendarController::class, 'restore'])->name('userAppointmentRestore');

Route::apiResources([
    'users'               => UserController::class,
    'users.appointments'  => UserCalendarController::class,
    'users.notifications' => UserNotificationController::class,
    'users.comments'      => UserCommentController::class,
    'map'                 => MapController::class,
]);

Route::post('/search', [SearchApiController::class, 'search'])->name('search');
Route::post('/search/location', [SearchApiController::class, 'searchLocation'])->name('search.location');
Route::post('/search/company', [SearchApiController::class, 'searchCompany'])->name('search.company');
Route::post('/search/lead', [SearchApiController::class, 'searchLead'])->name('search.lead');

Route::post('user/{user}/dialer/toggle', [UserFlagController::class, 'toggleDialer'])->name('toggleDialer');
Route::post('user/{user}/login/block/toggle', [UserFlagController::class, 'toggleBlockLogin'])->name('toggleBlockLogin');
Route::post('user/{user}/active/toggle', [UserFlagController::class, 'toggleActive'])->name('toggleActive');

Route::put('/users/{user}/notifications', [UserNotificationController::class, 'readAll'])->name('readAll');
Route::post('/users/{user}/appointments/{appointment}/attend', [UserCalendarController::class, 'attend'])->name('userAppointmentAttend');

Route::get('/calendar/event/{calendarEvent}', [CalendarEventApiController::class, 'getCalendarEvent'])->name('getCalendarEvent');
Route::post('/calendar/event/create', [CalendarEventApiController::class, 'createCalendarEvent'])->name('createCalendarEvent');
Route::delete('/calendar/event/delete/{calendarEvent}', [CalendarEventApiController::class, 'deleteCalendarEvent'])->name('deleteCalendarEvent');

Route::get('/users/{user}/leads', UserLeadController::class)->name('getLeads');
Route::put('/users/{user}/leads', CreateLeadController::class)->name('createLead');

Route::get('/experts', [ExpertController::class, 'get'])->name('getExperts');

Route::get('/categories/leads/string', [CategoryController::class, 'getAllLeadsCategoriesAsString'])->name('getAllLeadsCategoriesAsString');
Route::get('/categories/leads/object', [CategoryController::class, 'getAllLeadsCategoriesAsObject'])->name('getAllLeadsCategoriesAsObject');

Route::post('/location/{location}/activate', [CustomerProgressApiController::class, 'activateLocation'])->name('location.activate');

Route::put('/lead/{lead}/state/', [LeadStateChangeApiController::class, 'changeLeadState'])->name('lead.state.change');
Route::put('/lead/{lead}/state/followup/skip', [LeadStateChangeApiController::class, 'skipFollowUp'])->name('lead.state.followup.skip');

Route::post('/lead/{lead}/convert/new', [LeadCustomerConversionController::class, 'convertToNewCustomer'])->name('convertLeadToNewCustomer');
Route::post('/lead/{lead}/convert/existing/{company}', [LeadCustomerConversionController::class, 'convertToExistingCustomer'])->name('convertLeadToExistingCustomer');
