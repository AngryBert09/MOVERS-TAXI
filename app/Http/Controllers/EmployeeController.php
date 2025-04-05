<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Http;
use App\Models\TrainingAchievement;

class EmployeeController extends Controller
{
    public function index()
    {
        // Fetch Bearer token from .env
        $token = env('HR1_API_KEY');

        // Make API request to fetch employees
        $response = Http::withToken($token)->get('https://hr1.moverstaxi.com/api/v1/employees');

        // Check if request was successful
        if ($response->successful()) {
            $employees = $response->json();

            // Ensure $employees is an array before proceeding
            if (is_array($employees)) {
                // Fetch training achievements from local database for each employee
                foreach ($employees as $key => $employee) {
                    // Get achievements from the database using the employee's ID
                    $achievements = TrainingAchievement::where('employee_id', $employee['id'])->get();

                    // Add achievements to the employee data
                    $employees[$key]['achievements'] = $achievements;
                }

                return view('employee.index', compact('employees'));
            } else {
                // Handle the case where employees are not an array
                return back()->with('error', 'Unexpected response format.');
            }
        }

        // Handle API error
        return back()->with('error', 'Failed to fetch employees. Please try again later.');
    }



    public function getNewHired()
    {
        $employees = JobApplication::where('status', 'Hired')->with('jobPosting')->get();
        return view('employee.new-hired', compact('employees'));
    }
}
