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

        // Get latest job postings
        $latestJobPosts = JobPosting::latest('created_at')->take(5)->get();

        // Group job applications by month
        $jobApplicationsByMonth = JobApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Group hired candidates by month
        $hiredByMonth = JobApplication::where('status', 'Hired')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Group active training sessions by month
        $activeTrainingByMonth = Training::where('status', 'Active')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $jobs = JobPosting::with('department')->get();
        $departments = Department::select('id', 'department_name')->get();

        return view('dashboard', compact(
            'jobApplicationsCount',
            'hiredCount',
            'jobPostingsCount',
            'latestJobPosts',
            'activeTrainingCount',
            'jobs',
            'departments',
            'jobApplicationsByMonth',
            'hiredByMonth',
            'activeTrainingByMonth'
        ));
    }
}
