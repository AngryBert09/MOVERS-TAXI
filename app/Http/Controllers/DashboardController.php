<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Training;
use App\Models\Department;


class DashboardController extends Controller
{
    public function index()
    {
        // Get total count of job applications
        $jobApplicationsCount = JobApplication::count();

        // Get count of hired candidates
        $hiredCount = JobApplication::where('status', 'Hired')->count();

        // Get total count of job postings
        $jobPostingsCount = JobPosting::count();

        // Get count of active training sessions
        $activeTrainingCount = Training::where('status', 'Active')->count();

        // Get latest job posting
        $latestJobPosts = JobPosting::latest('created_at')->take(5)->get();

        $jobs = JobPosting::with('department')->get(); // Eager load departments
        $departments = Department::select('id', 'department_name')->get(); // Select only needed columns

        // Return view with data
        return view('dashboard', compact('jobApplicationsCount', 'hiredCount', 'jobPostingsCount', 'latestJobPosts', 'activeTrainingCount', 'jobs', 'departments'));
    }
}
