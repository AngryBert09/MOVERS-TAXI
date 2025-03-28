<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobApplicationController;

Route::prefix('v1')->group(function () {
    Route::get('/employee', [JobApplicationController::class, 'index']);
    Route::post('/job-applications', [JobApplicationController::class, 'store']);
    // Add other routes as needed
});
