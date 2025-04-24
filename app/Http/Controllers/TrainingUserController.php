<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Training;
use Illuminate\Support\Facades\Log;

class TrainingUserController extends Controller
{


    public function index()
    {
        $token = env('HR1_API_KEY');
        $userEmail = Auth::user()->email;

        try {
            // Log the start of the process
            Log::info('Starting index method in TrainingUserController', ['userEmail' => $userEmail]);

            // Fetch employees from external API
            $response = Http::withToken($token)->get('https://hr1.moverstaxi.com/api/v1/employees');

            if (!$response->successful()) {
                Log::error('Failed to fetch employee data from API', ['status' => $response->status()]);
                return back()->withErrors(['api' => 'Failed to fetch employee data.']);
            }

            $employees = collect($response->json());
            Log::info('Fetched employees from API', ['employeeCount' => $employees->count()]);

            // Find matching employee by email
            $employee = $employees->firstWhere('email', $userEmail);

            if (!$employee) {
                Log::warning('Authenticated user not found in employee database', ['userEmail' => $userEmail]);
                return back()->withErrors(['user' => 'Authenticated user not found in employee database.']);
            }

            $employeeId = $employee['id'];
            Log::info('Employee found', ['employeeId' => $employeeId]);

            // Check if employee ID exists in Training model
            $trainings = Training::where('trainee_id', $employeeId)->get();

            if ($trainings->isEmpty()) {
                Log::warning('No training records found for this employee', ['employeeId' => $employeeId]);
                return back()->withErrors(['training' => 'No training records found for this employee.']);
            }

            Log::info('Training records found', ['trainingCount' => $trainings->count()]);

            // Return the training data to the view
            return view('portal.training.index', [
                'trainings' => $trainings,
                'employees' => $employees,
            ]);
        } catch (\Exception $e) {
            Log::error('Exception occurred in index method', ['exceptionMessage' => $e->getMessage()]);
            return back()->withErrors(['exception' => $e->getMessage()]);
        }
    }
}
