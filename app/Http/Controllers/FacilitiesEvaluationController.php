<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class FacilitiesEvaluationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Check if an evaluation already exists for this user
        $hasSubmitted = DB::table('facility_evaluations')
            ->where('employee_id', $userId)
            ->exists();

        return view('evaluations.facilities', [
            'hasSubmitted' => $hasSubmitted,
            'employeeId' => $userId, // Pass the current user ID
        ]);
    }




    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'question1' => 'required|integer|between:1,5',
            'question2' => 'required|integer|between:1,5',
            'question3' => 'required|integer|between:1,5',
            'question4' => 'required|integer|between:1,5',
            'question5' => 'required|integer|between:1,5',
            'question6' => 'required|integer|between:1,5',
            'question7' => 'required|integer|between:1,5',
            'question8' => 'required|integer|between:1,5',
            'question9' => 'required|integer|between:1,5',
            'question10' => 'required|integer|between:1,5',
        ]);

        try {
            // Insert the evaluation data into the 'facility_evaluations' table
            DB::table('facility_evaluations')->insert([
                'employee_id' => Auth::id(),  // Add the authenticated user's ID
                'facility' => 'HR Facility',
                'question1_cleanliness' => $request->input('question1'),
                'question2_equipment_availability' => $request->input('question2'),
                'question3_technology_functionality' => $request->input('question3'),
                'question4_workspace_comfort' => $request->input('question4'),
                'question5_safety_preparedness' => $request->input('question5'),
                'question6_restroom_accessibility' => $request->input('question6'),
                'question7_internet_reliability' => $request->input('question7'),
                'question8_break_area_availability' => $request->input('question8'),
                'question9_storage_organization' => $request->input('question9'),
                'question10_general_appearance' => $request->input('question10'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Evaluation submitted successfully!');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->withErrors(['error' => 'An error occurred while submitting the evaluation. Please try again.']);
        }
    }

    public function results()
    {
        // Fetch all evaluation data from the 'facility_evaluations' table
        $evaluations = DB::table('facility_evaluations')->get();

        // Pass the data to the view
        return view('evaluations.facilities-results', [
            'profilings' => $evaluations,
        ]);
    }
}
