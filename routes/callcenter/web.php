<?php

use LocalheroPortal\Callcenter\Http\Controllers\Admin\AgentStatsController;
use LocalheroPortal\Callcenter\Http\Controllers\Admin\ExpertController as AdminExpertController;
use LocalheroPortal\Callcenter\Http\Controllers\AgentController;
use LocalheroPortal\Callcenter\Http\Controllers\ExpertController;
use LocalheroPortal\Callcenter\Http\Controllers\ExpertLeadController;
use LocalheroPortal\Callcenter\Http\Controllers\LeadController;

Route::get('/callcenter/wiedervorlagen', [AgentController::class, 'recalls'])->name('callcenter.recalls');
Route::post('/experts/assign', [AdminExpertController::class, 'assign'])->name('experts.assign');

Route::resource('experts', ExpertController::class)->middleware('can:view-experts');
Route::resource('experts.leads', ExpertLeadController::class)->middleware('can:view-experts');
Route::resource('/leads', LeadController::class);
Route::get('/leads/{lead}/accept', [LeadController::class, 'accept'])->name('lead.accept');
Route::get('/leads/{lead}/reject', [LeadController::class, 'reject'])->name('lead.reject');

Route::prefix('admin')->name('admin.')->middleware('can:supervise-callcenter')->group(function () {
    Route::get('agents', [AgentStatsController::class, 'getAgentStats'])->name('agents');
    Route::post('agents', [AgentStatsController::class, 'getAgentStatsWithDates']);
    Route::resources([
        'experts' => AdminExpertController::class,
    ]);
});

Route::get('/callcenter/batch-import-google', [LeadController::class, 'batchImportFromGooglePlaces'])->name('callcenter.batch-import');
Route::get('/callcenter', [AgentController::class, 'index'])->name('callcenter.index');
Route::delete('/callcenter/{lead}', [AgentController::class, 'destroy'])->name('callcenter.destroy');
Route::get('/callcenter/{lead}', [AgentController::class, 'show'])->name('callcenter.show');
Route::post('/callcenter', [AgentController::class, 'store'])->name('callcenter.store');
Route::post('/callcenter/{id}', [AgentController::class, 'restore'])->name('callcenter.restore');
