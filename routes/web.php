<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\InquiriesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;

Route::middleware('guest')->group(function () {
    Route::name('landing.')->group(function () {
        Route::get('/', function () {
            return view('landing-pages.index');
        })->name('page');

        Route::get('/contact-us', [CompanyController::class, 'contacts'])->name('contact');
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
    Route::post('/apply-job', [ApplicantController::class, 'applyJob'])->name('apply.job');

    //INQUIRIES
    Route::post('/inquiries/store', [InquiriesController::class, 'store'])->name('inquiries.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('admin');;


Route::get('/job-view', function () {
    return view('job-view');
})->name('job-view');

Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //JOBS
    Route::get('/manage-jobs', [JobController::class, 'getManageJobs'])->name('jobs.manage');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::put('/jobs/update/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

    //APPLICANTS
    Route::get('/job-postings/applicants', [ApplicantController::class, 'index'])
        ->name('applicants');
    Route::get('/job-postings/{id}/applicants', [ApplicantController::class, 'getApplicants'])
        ->name('job.applicants');
    Route::post('/applicant/update-status', [ApplicantController::class, 'updateStatus'])->name('update.applicant.status');
    Route::post('/schedule-interview', [ApplicantController::class, 'scheduleInterview'])->name('schedule.interview');

    //TRAININGS
    Route::get('/training-list', [TrainingController::class, 'getTrainingList'])->name('training.list');
    Route::get('/trainers', [TrainingController::class, 'getTrainers'])->name('training.trainers');
    Route::get('/training-type', [TrainingController::class, 'getTrainingTypes'])->name('training.types');
    Route::post('/training-types/store', [TrainingController::class, 'storeTrainingType'])->name('training-types.store');
    Route::put('/training-types/update/{id}', [TrainingController::class, 'updateTrainingType'])->name('training-types.update');
    Route::delete('/training-types/delete/{id}', [TrainingController::class, 'destroyTrainingType'])->name('training-types.destroy');
    Route::post('/trainers/store', [TrainingController::class, 'storeTrainer'])->name('trainers.store');
    Route::post('/trainers/update/{id}', [TrainingController::class, 'updateTrainer'])->name('trainers.update');
    Route::delete('/trainers/{id}', [TrainingController::class, 'destroyTrainer'])->name('trainers.destroy');
    Route::post('/trainings', [TrainingController::class, 'storeTraining'])->name('trainings.store');
    Route::put('/trainings/update/{id}', [TrainingController::class, 'updateTraining'])->name('trainings.update');

    //DEPARTMENTS
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    Route::post('/departments/store', [DepartmentController::class, 'store'])->name('departments.store');

    //BUDGET
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets');
    Route::post('/budgets/request', [BudgetController::class, 'store'])->name('budget.store');

    //INQUIRIES
    Route::get('/inquiries', [InquiriesController::class, 'index'])->name('inquiries');
    Route::post('/inquiries/{id}/reply', [InquiriesController::class, 'reply'])->name('inquiries.reply');

    //PERFORMANCE
    Route::get('/performance-evaluation', [PerformanceController::class, 'index'])->name('performance.index');
    Route::post('/performance-evaluation/store', [PerformanceController::class, 'store'])->name('performance.store');

    //NEW HIRED
    Route::get('/new-hired', [EmployeeController::class, 'index'])->name('employee.index');

    //COMPANY SETTINGS
    Route::get('/company-settings', [CompanyController::class, 'index'])->name('company.index');
    Route::put('/settings/update', [CompanyController::class, 'update'])->name('company.update');
});
