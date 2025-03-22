<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\PerformanceEvaluation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PerformanceController extends Controller
{
    public function index()
    {
        $trainees = JobApplication::where('status', 'Hired')->with('jobPosting')->get();
        return view('performance.appraisal', compact('trainees'));
    }

    public function store(Request $request)
    {
        try {
            // Debugging: Log the incoming request data
            Log::info('Store Performance Evaluation Request:', $request->all());

            // Validate the form data
            $request->validate([
                'trainee_id' => 'required|exists:job_applications,id',
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
            ], [
                'trainee_id.required' => 'The trainee ID is required.',
                'trainee_id.exists' => 'The selected trainee does not exist.',
                'status.in' => 'The status must be either Active or Inactive.',
                // Add custom error messages for other fields as needed
            ]);

            // Check if a performance evaluation already exists for the trainee
            $existingEvaluation = PerformanceEvaluation::where('trainee_id', $request->trainee_id)->first();
            if ($existingEvaluation) {
                return redirect()->back()->with('error', 'A performance evaluation already exists for this trainee.');
            }

            // Create a new PerformanceAppraisal record
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

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Evaluation saved successfully!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error storing performance evaluation: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while saving the performance evaluation. Please try again. Error: ' . $e->getMessage());
        }
    }
}
