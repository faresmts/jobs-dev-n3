<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/v1/reports', ReportController::class)->only('index', 'store', 'destroy')->names('reports');
Route::post('/v1/reports/store-many', [ReportController::class, 'storeManyReports'])->name('reports.store-many');