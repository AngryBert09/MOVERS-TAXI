<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;

Route::middleware('guest')->group(function () {
    Route::name('landing.')->group(function () {
        Route::get('/', function () {
            return view('landing-pages.index');
        })->name('page');

        Route::get('/contact-us', function () {
            return view('landing-pages.contact');
        })->name('contact');
    });

    // Authentication Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');;


    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

    // Email Verification Routes
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('auth.verify');
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');

    //JOBS
    Route::get('/job-vacancies', [JobController::class, 'getJobVacancies'])->name('jobs.vacancies');
    Route::get('/job-details/{id}', [JobController::class, 'showJobDetails'])->name('jobs.details');
    Route::post('/apply-job', [JobController::class, 'applyJob'])->name('apply.job');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('admin');;


Route::get('/job-view', function () {
    return view('job-view');
})->name('job-view');

Route::middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //JOBS
    Route::get('/manage-jobs', [JobController::class, 'getManageJobs'])->name('jobs.manage');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/job-postings/{id}/applicants', [JobController::class, 'getApplicants'])
        ->name('job.applicants');
    Route::post('/applicant/update-status', [JobController::class, 'updateStatus'])->name('update.applicant.status');
});
