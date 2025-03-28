<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function index()
    {
        // Fetch Bearer token from .env
        $token = env('HR1_API_KEY');

        // Make API request
        $response = Http::withToken($token)->get('https://hr1.moverstaxi.com/api/v1/employees');

        // Check if request was successful
        if ($response->successful()) {
            $employees = $response->json();
            return view('employee.index', compact('employees'));
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
