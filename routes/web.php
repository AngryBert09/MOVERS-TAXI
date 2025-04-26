<?php

use Illuminate\Support\Facades\Artisan;
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
use App\Http\Controllers\ResumeAnalyzerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicantUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicantFileController;
use App\Http\Controllers\TrainingUserController;
use App\Http\Controllers\FacilitiesEvaluationController;
use App\Http\Controllers\DocumentController;

Route::middleware('guest')->group(function () {
    Route::name('landing.')->group(function () {
        Route::get('/', [CompanyController::class, 'landing'])->name('index');
        Route::get('/contact-us', [CompanyController::class, 'contacts'])->name('contact');
    });

    Route::get('/application-status', function () {
        return view('application-status');
    })->name('search');

    // Authentication Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');


    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

    // Email Verification Routes
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('auth.verify');
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');


    //INQUIRIES
    Route::post('/inquiries/store', [InquiriesController::class, 'store'])->name('inquiries.store');

    //APPLICATION STATUS
    Route::get('/search-application', [ApplicantController::class, 'searchApplication'])->name('application.search');
});


//JOBS
Route::get('/job-vacancies', [JobController::class, 'getJobVacancies'])->name('jobs.vacancies');
Route::get('/job-details/{id}', [JobController::class, 'showJobDetails'])->name('jobs.details');
Route::post('/apply-job', [ApplicantController::class, 'applyJob'])->name('apply.job');



Route::get('/2fa/verify', [AuthController::class, 'show2faForm'])->name('2fa.verify');
Route::post('/2fa/verify', [AuthController::class, 'verify2fa'])->name('2fa.check');
Route::get('/2fa/resend', [AuthController::class, 'resend2fa'])->name('2fa.resend');


Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');


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
    Route::post('/analyze-resume', [ResumeAnalyzerController::class, 'analyzeResume']);
    Route::post('/send-message', [ApplicantController::class, 'sendMessage'])->name('applicant.sendMessage');
    Route::get('/onboarding-applicants', [ApplicantController::class, 'onboarding'])
        ->name('applicants.onboarding');
    Route::get('/applicant-files', [ApplicantFileController::class, 'index'])
        ->name('applicant.files');
    Route::post('/applications/fail', [ApplicantController::class, 'failApplicant'])->name('applications.fail');
    Route::post('/applications/{id}/comply-date', [ApplicantController::class, 'submissionRequirements'])->name('applications.submission.requirements');

    //FACILITIES EVALUATION
    Route::get('/evaluate/facilities', [FacilitiesEvaluationController::class, 'index'])->name('facilities');
    Route::get('/evaluations/facilities/results', [FacilitiesEvaluationController::class, 'results'])->name('facilities.results');

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
    Route::delete('/training/{id}', [TrainingController::class, 'destroyTraining'])->name('trainings.destroy');
    Route::get('/for-training', [TrainingController::class, 'getEmployees'])->name('training.for-training');
    Route::get('/trainings/{id}/certificate', [TrainingController::class, 'viewCertificate'])->name('trainings.certificate');



    //DEPARTMENTS
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    Route::post('/departments/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/update/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    Route::get('/departments/api/{name}', [DepartmentController::class, 'show'])->name('departments.employees');


    //BUDGET
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets');
    Route::post('/budgets/request', [BudgetController::class, 'store'])->name('budget.store');
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])->name('budget.destroy');
    Route::get('/used-budget', [BudgetController::class, 'getUsedBudget'])->name('budget.used');
    Route::post('/budget/store', [BudgetController::class, 'storeBudgetUsage'])->name('budget.usage.store');


    //INQUIRIES
    Route::get('/inquiries', [InquiriesController::class, 'index'])->name('inquiries');
    Route::post('/inquiries/{id}/reply', [InquiriesController::class, 'reply'])->name('inquiries.reply');

    //PERFORMANCE
    Route::get('/performance-evaluation', [PerformanceController::class, 'index'])->name('performance.index');
    Route::post('/performance-evaluation/store', [PerformanceController::class, 'store'])->name('performance.store');
    Route::get('/performance-results', [PerformanceController::class, 'results'])->name('performance.results');
    Route::post('/performance-evaluation/store/trainer', [PerformanceController::class, 'storeTrainerEval'])->name('performance.trainer.store');

    //EMPLOYEES
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::get('/new-hired', [EmployeeController::class, 'getNewHired'])->name('employee.new-hired');
    Route::get('/employee-attendance', [EmployeeController::class, 'fetchAttendance'])->name('employees.attendance');
    Route::get('/employee/attendance', [EmployeeController::class, 'searchAttendance'])->name('employee.attendance.search');


    //COMPANY SETTINGS
    Route::get('/company-settings', [CompanyController::class, 'index'])->name('company.index');
    Route::put('/settings/update', [CompanyController::class, 'update'])->name('company.update');

    //PROFILE
    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //USERS
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    //DOCUMENTS
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
});

//FACILITIES EVALUATION
Route::middleware('auth')->group(function () {
    Route::post('/facility-evaluation/store', [FacilitiesEvaluationController::class, 'store'])->name('facility-evaluation.store');

    Route::get('/applicant/dashboard', [ApplicantUserController::class, 'index'])->name('applicant.dashboard');
    Route::delete('/application/{id}/withdraw', [ApplicantUserController::class, 'withdraw'])->name('application.withdraw');
    Route::post('/applications/{application}/requirements', [ApplicantUserController::class, 'uploadRequirements'])->name('applications.requirements.upload');

    //APPLICANT FACILITY EVALUATION
    Route::get('/evaluate/facility', [ApplicantUserController::class, 'evaluate'])->name('applicant.facility');

    //MY TRAININGS
    Route::get('/current-training', [TrainingUserController::class, 'index'])->name('my.training');
});



Route::get('/create-symlink', function () {
    symlink(storage_path('/app/public'), public_path('storage'));
    echo "Symlink Created. Thanks";
});


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'Application cache, config, routes, and views cleared!';
})->middleware('auth'); // Optional: restrict with middleware


Route::get('/run-config-cache', function () {


    Artisan::call('config:cache');
    return 'Config cache refreshed!';
})->middleware('auth'); // Optional: restrict with middleware
