<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\PerformanceEvaluation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PerformanceController extends Controller
{
    public function index()
    {
        $trainees = []; // Fetch trainees from API
        $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
        $apiToken = env('HR1_API_KEY'); // Store token in .env

        $response = Http::withToken($apiToken)->get($apiUrl);
        if ($response->successful()) {
            $trainees = $response->json(); // Convert response to an array
        }

        // Fetch existing evaluations
        $evaluations = PerformanceEvaluation::pluck('trainee_id')->toArray();


        return view('performance.appraisal', compact('trainees', 'evaluations'));
    }


    public function store(Request $request)
    {
        try {
            // Debugging: Log the incoming request data
            Log::info('Store Performance Evaluation Request:', $request->all());

            // Fetch employees from API
            $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
            $apiToken = env('HR1_API_KEY');

            $response = Http::withToken($apiToken)->get($apiUrl);

            if ($response->successful()) {
                $employees = collect($response->json()); // Convert response to collection
            } else {
                Log::error('Failed to fetch employees from API');
                return redirect()->back()->with('error', 'Failed to retrieve employees. Please try again.');
            }

            // Validate request
            $request->validate([
                'trainee_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($employees) {
                        if (!$employees->contains('id', $value)) {
                            $fail('The selected trainee does not exist.');
                        }
                    }
                ],
                'customer_experience' => 'required|string|max:255',
                'marketing' => 'required|string|max:255',
                'management' => 'required|string|max:255',
                'administration' => 'required|string|max:255',
                'presentation_skill' => 'required|string|max:255',
                'quality_of_work' => 'required|string|max:255',
                'efficiency' => 'required|string|max:255',
                'integrity' => 'required|string|max:255',
                'professionalism' => 'required|string|max:255',
                'team_work' => 'required|string|max:255',
                'critical_thinking' => 'required|string|max:255',
                'conflict_management' => 'required|string|max:255',
                'attendance' => 'required|string|max:255',
                'ability_to_meet_deadline' => 'required|string|max:255',
                'status' => 'required|in:Active,Inactive',
            ]);

            // Check if a performance evaluation already exists for the trainee
            $existingEvaluation = PerformanceEvaluation::where('trainee_id', $request->trainee_id)->first();
            if ($existingEvaluation) {
                return redirect()->back()->with('error', 'A performance evaluation already exists for this trainee.');
            }

            // Create a new Performance Evaluation record
            PerformanceEvaluation::create([
                'trainee_id' => $request->trainee_id,
                'evaluation_date' => Carbon::now()->startOfDay(),
                'customer_experience' => $request->customer_experience,
                'marketing' => $request->marketing,
                'management' => $request->management,
                'administration' => $request->administration,
                'presentation_skill' => $request->presentation_skill,
                'quality_of_work' => $request->quality_of_work,
                'efficiency' => $request->efficiency,
                'integrity' => $request->integrity,
                'professionalism' => $request->professionalism,
                'team_work' => $request->team_work,
                'critical_thinking' => $request->critical_thinking,
                'conflict_management' => $request->conflict_management,
                'attendance' => $request->attendance,
                'ability_to_meet_deadline' => $request->ability_to_meet_deadline,
                'status' => $request->status,
            ]);

            return redirect()->back()->with('success', 'Evaluation saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing performance evaluation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the performance evaluation. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function results()
    {
        // Fetch all trainees' results from the PerformanceEvaluation model
        $trainees = PerformanceEvaluation::all();

        // Pass the retrieved data to the view
        return view('performance.results', compact('trainees'));
    }
}
