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

    public function fetchAttendance()
    {
        try {
            // Call the external API
            $response = Http::get('https://hr3.moverstaxi.com/wemovers/api/attendance_data_adjustment.php');

            // Check if the response is successful
            if ($response->successful()) {
                $attendanceData = $response->json(); // Decode JSON data

                // Pass data to the employee.attendancee view
                return view('employee.attendance', ['attendances' => $attendanceData]);
            } else {
                return redirect()->back()->with('error', 'Failed to fetch attendance data.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function searchAttendance(Request $request)
    {
        $employeeName = $request->input('employee_name');
        $month = $request->input('month');
        $year = $request->input('year');

        // Call the external API
        $response = Http::get('https://hr3.moverstaxi.com/wemovers/api/attendance_data_adjustment.php');

        $attendanceData = collect(json_decode($response->body(), true));

        // Filter by name if provided
        if ($employeeName) {
            $attendanceData = $attendanceData->filter(function ($item) use ($employeeName) {
                return stripos($item['emp_name'], $employeeName) !== false;
            });
        }

        // Filter by month/year if provided
        if ($month || $year) {
            $attendanceData = $attendanceData->filter(function ($item) use ($month, $year) {
                $logDate = \Carbon\Carbon::parse($item['log_date']);
                return (!$month || $logDate->month == $month) &&
                    (!$year || $logDate->year == $year);
            });
        }

        // Group attendance by user_id for the calendar view
        $grouped = $attendanceData->groupBy('user_id');

        return view('employee.attendancee', compact('grouped', 'attendanceData'));
    }
}
