<?php

use LocalheroPortal\Callcenter\Http\Controllers\Api\AppointmentController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\CheckCustomerController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\CityController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\ContactController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\EvaluationGraphController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\ExpertLeadController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\LeadBatchUpdateController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\LeadCommentController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\LeadController;
use LocalheroPortal\Callcenter\Http\Controllers\Api\LeadIntervalController;


Route::get('/leads/cities', CityController::class);
Route::get('/leads/categories', [LeadController::class, 'categories']);
Route::post('/leads/batch', LeadBatchUpdateController::class);
Route::post('/leads/convert/check', CheckCustomerController::class);
Route::apiResources([
    'leads'           => LeadController::class,
    'leads.comments'  => LeadCommentController::class,
    'leads.intervals' => LeadIntervalController::class,
    'experts.leads'   => ExpertLeadController::class,
    'appointments'    => AppointmentController::class,
]);

Route::get('/contacts', ContactController::class);
Route::get('/graph', [EvaluationGraphController::class, 'endpoint']);
Route::post('/callcenter/store-lead', [LeadController::class, 'store']);
Route::put('/leads/{lead}/updateExpertStatus', [LeadController::class, 'updateExpertStatus'])->name('leads.updateExpertStatus');
Route::get('/leads/{lead}/lastAppointment', [LeadController::class, 'getLastAppointment'])->name('api.leads.lastAppointment');
Route::put('/leads/{lead}/expert/{user}', [LeadController::class, 'changeExpert'])->name('leads.expert.change');
Route::put('/leads/{lead}/revertBlacklist', [LeadController::class, 'revertBlacklist'])->name('leads.revertBlacklist');

Route::get('/expert/pipeline/stats', [ExpertLeadController::class, 'getExpertPipelineStats'])->name('ExpertPipelineStats');

////Route::get('experts.leads', [ExpertLeadController::class, 'index']);
//Route::get('experts.leads', [ExpertLeadController::class, 'index']);
