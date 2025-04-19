<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BudgetController extends Controller
{
    public function index()
    {
        // Fetch all budget requests
        $requests = DB::table('budget_requests')->get();

        // Get total approved budget
        $totalApprovedBudget = DB::table('budget_requests')
            ->where('status', 'Approved')
            ->sum('amount');

        // Get total expenses from training costs
        $totalTrainingCost = DB::table('trainings')->sum('training_cost');

        // Calculate remaining budget
        $remainingBudget = $totalApprovedBudget - $totalTrainingCost;

        return view('budgets.expenses', compact('requests', 'totalApprovedBudget', 'remainingBudget'));
    }

    public function getUsedBudget()
    {
        return view('budgets.used-budget');
    }


    public function store(Request $request)
    {
        // Custom validation messages
        $messages = [
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'purpose.required' => 'The purpose field is required.',
            'purpose.string' => 'The purpose must be a text.',
            'purpose.max' => 'The purpose must not exceed 500 characters.',
            'file.required' => 'A file is required.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'The file must be a PDF, JPG, PNG, DOC, or DOCX.',
            'file.max' => 'The file size must not exceed 2MB.',
        ];

        // Validate the incoming request data
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:500',
            'file' => 'required|file|mimes:pdf,jpg,png,doc,docx|max:2048', // Restrict file types and size
        ], $messages);

        try {
            // Handle file upload
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('budget_files', 'public'); // Store file in storage/app/public/budget_files
            } else {
                return redirect()->back()->withErrors(['file' => 'File upload failed. Please try again.'])->withInput();
            }

            // Insert the budget request into the database
            DB::table('budget_requests')->insert([
                'amount' => $validatedData['amount'],
                'purpose' => $validatedData['purpose'],
                'file_path' => $filePath,
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Budget request submitted successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Budget request submission failed: ' . $e->getMessage());

            // Friendly error message for the user
            return redirect()->back()->withErrors(['error' => 'Something went wrong while processing your request. Please try again later.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            // Find the budget request
            $budgetRequest = DB::table('budget_requests')->where('id', $id)->first();

            if (!$budgetRequest) {
                return redirect()->back()->withErrors(['error' => 'Budget request not found.']);
            }

            // Delete the associated file if it exists
            if ($budgetRequest->file_path) {
                Storage::disk('public')->delete($budgetRequest->file_path);
            }

            // Delete the budget request from the database
            DB::table('budget_requests')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Budget request deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete budget request: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting the budget request. Please try again.']);
        }
    }
}
