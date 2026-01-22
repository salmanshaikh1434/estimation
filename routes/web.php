<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        // Get stats for dashboard
        $projectsCount = \App\Models\Project::count();
        $estimationsCount = \App\Models\Estimation::count();
        $ratesCount = \App\Models\DsrRate::count();
        
        return view('dashboard', compact('projectsCount', 'estimationsCount', 'ratesCount'));
    })->name('dashboard');

    // Projects
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);

    // Estimations
    Route::get('projects/{project}/estimations/create', [\App\Http\Controllers\EstimationController::class, 'create'])
        ->name('estimations.create');
    Route::post('projects/{project}/estimations', [\App\Http\Controllers\EstimationController::class, 'store'])
        ->name('estimations.store');
    Route::get('estimations/{estimation}', [\App\Http\Controllers\EstimationController::class, 'show'])
        ->name('estimations.show');
    Route::get('estimations/{estimation}/edit', [\App\Http\Controllers\EstimationController::class, 'edit'])
        ->name('estimations.edit');
    Route::get('estimations/{estimation}/manage', [\App\Http\Controllers\EstimationController::class, 'manage'])
        ->name('estimations.manage');
    
    // Lead management routes
    Route::get('estimations/{estimation}/leads', [\App\Http\Controllers\EstimationLeadController::class, 'index'])->name('estimations.leads');
    Route::post('estimations/{estimation}/leads', [\App\Http\Controllers\EstimationLeadController::class, 'store'])->name('estimations.leads.store');
    Route::get('estimations/{estimation}/leads/totals', [\App\Http\Controllers\EstimationLeadController::class, 'getTotals'])->name('estimations.leads.totals');
    
    Route::put('estimations/{estimation}', [\App\Http\Controllers\EstimationController::class, 'update'])
        ->name('estimations.update');
    Route::delete('estimations/{estimation}', [\App\Http\Controllers\EstimationController::class, 'destroy'])
        ->name('estimations.destroy');

    // Estimation Items
    Route::post('estimations/{estimation}/items', [\App\Http\Controllers\EstimationItemController::class, 'store'])
        ->name('estimation-items.store');
    Route::put('estimation-items/{item}', [\App\Http\Controllers\EstimationItemController::class, 'update'])
        ->name('estimation-items.update');
    Route::delete('estimation-items/{item}', [\App\Http\Controllers\EstimationItemController::class, 'destroy'])
        ->name('estimation-items.destroy');
    Route::post('estimations/{estimation}/items/reorder', [\App\Http\Controllers\EstimationItemController::class, 'reorder'])
        ->name('estimation-items.reorder');

    // Rates (DSR, SSR, WRD)
    Route::get('rates', [\App\Http\Controllers\RateController::class, 'index'])->name('rates.index');
    Route::get('rates/{rateType}/{id}', [\App\Http\Controllers\RateController::class, 'show'])->name('rates.show');

    // API Routes for AJAX calls
    Route::get('api/rates/{type}/search', [\App\Http\Controllers\Api\RateApiController::class, 'search'])
        ->name('api.rates.search');
    Route::get('api/rates/{type}/{id}', [\App\Http\Controllers\Api\RateApiController::class, 'getRate'])
        ->name('api.rates.get');
    Route::get('api/calculation-formulas', [\App\Http\Controllers\Api\RateApiController::class, 'formulas'])
        ->name('api.formulas.index');

    // Measurements
    Route::get('estimation-items/{item}/measurements', [\App\Http\Controllers\MeasurementController::class, 'index'])
        ->name('measurements.index');
    Route::post('estimation-items/{item}/measurements', [\App\Http\Controllers\MeasurementController::class, 'store'])
        ->name('measurements.store');
    Route::delete('estimation-items/{item}/measurements', [\App\Http\Controllers\MeasurementController::class, 'destroy'])
        ->name('measurements.destroy');
});

require __DIR__.'/settings.php';
