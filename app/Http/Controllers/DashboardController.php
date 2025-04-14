<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Training;
use App\Models\Department;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{


    public function index()
    {
        // Fetch employee count from external API
        $employeeCount = 0;
        try {
            $response = Http::withToken(env('HR1_API_KEY'))
                ->get('https://hr1.moverstaxi.com/api/v1/employees');

            if ($response->successful()) {
                $employeeData = $response->json();
                $employeeCount = is_array($employeeData) ? count($employeeData) : 0;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch employee data: ' . $e->getMessage());
        }

        // Continue with other counts
        $jobApplicationsCount = JobApplication::count();
        $hiredCount = JobApplication::where('status', 'Hired')->count();
        $jobPostingsCount = JobPosting::count();
        $activeTrainingCount = Training::where('status', 'Active')->count();
        $latestJobPosts = JobPosting::latest('created_at')->take(5)->get();

        $jobApplicationsByMonth = JobApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $hiredByMonth = JobApplication::where('status', 'Hired')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $activeTrainingByMonth = Training::where('status', 'Active')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $jobs = JobPosting::with('department')->get();
        $departments = Department::select('id', 'department_name')->get();

        return view('dashboard', compact(
            'employeeCount',
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
