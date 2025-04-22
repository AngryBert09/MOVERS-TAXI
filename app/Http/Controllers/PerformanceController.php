<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\PerformanceEvaluation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class PerformanceController extends Controller
{
    public function index()
    {
        $trainees = []; // Fetch trainees from API
        $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
        $apiToken = env('HR1_API_KEY'); // Store token in .env

        $response = Http::withToken($apiToken)->get($apiUrl);

        if ($response->successful()) {
            // Get Job Applications where status is 'hired'
            $hiredApplications = JobApplication::where('status', 'hired')->pluck('status', 'id');

            // Filter and append 'hired_status' to each trainee
            $trainees = collect($response->json())->filter(function ($employee) {
                return isset($employee['department']) && $employee['department'] === 'HR';
            })->map(function ($employee) use ($hiredApplications) {
                $employee['Hired'] = $hiredApplications[$employee['id']] ?? 'not hired';
                return $employee;
            })->values()->toArray();
        }

        // Fetch existing evaluations
        $evaluations = PerformanceEvaluation::pluck('employee_id')->toArray();

        return view('evaluations.appraisal', compact('trainees', 'evaluations'));
    }


    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'trainee_id' => 'required',
            'performance' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $requiredCriteria = [
                        'punctuality',
                        'quality',
                        'communication',
                        'feedback',
                        'problem_solving',
                        'teamwork',
                        'attitude',
                        'adaptability',
                        'time_management',
                        'behavior'
                    ];

                    $missing = array_diff($requiredCriteria, array_keys($value));

                    if (!empty($missing)) {
                        $fail('The following evaluation criteria are missing: ' . implode(', ', $missing));
                    }
                }
            ],
            'department' => 'required|string|max:255',
            'performance.*' => 'required|integer|between:1,5',
            'supervisor_feedback' => 'nullable|string|max:2000',
        ];

        // Custom validation messages
        $messages = [
            'performance.*.required' => 'All performance ratings must be provided.',
            'performance.*.between' => 'Ratings must be between 1 and 5.',
        ];

        // Validate the request
        try {
            $validated = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validation failed. Please correct the errors and try again.');
        }

        // Additional check for null values (though validation should catch this)
        if (in_array(null, $validated['performance'], true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'All performance ratings must be provided.');
        }

        // Begin database transaction
        DB::beginTransaction();

        try {


            // Store each performance rating
            foreach ($validated['performance'] as $criteria => $rating) {
                PerformanceEvaluation::create([
                    'employee_id' => $validated['trainee_id'],
                    'department' => $validated['department'],
                    'category' => 'performance',
                    'criteria' => $criteria,
                    'rating' => $rating,
                ]);
            }

            // Store supervisor feedback separately if provided
            if (!empty($validated['supervisor_feedback'])) {
                PerformanceEvaluation::updateOrCreate(
                    [
                        'employee_id' => $validated['trainee_id'],
                        'category' => 'feedback',
                        'criteria' => 'supervisor_feedback',
                        'department' => $validated['department'],
                    ],
                    [
                        'rating' => 0,
                        'supervisor_feedback' => $validated['supervisor_feedback'],
                    ]
                );
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()
                ->with('success', 'Performance evaluation saved successfully.');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save evaluation. Error: ' . $e->getMessage());
        }
    }


    public function results()
    {
        $evaluations = PerformanceEvaluation::all();
        $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
        $apiToken = env('HR1_API_KEY');

        $employees = collect();

        try {
            $response = Http::withToken($apiToken)->get($apiUrl);

            if ($response->successful()) {
                $employees = collect($response->json());
            } else {
                Log::error('Failed to fetch employees', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching employees from API', ['message' => $e->getMessage()]);
        }

        // Group evaluations by trainee_id and pivot
        $trainees = $evaluations->groupBy('employee_id')->map(function ($group, $traineeId) use ($employees) {
            $employee = $employees->firstWhere('id', $traineeId);
            $fullName = $employee
                ? $employee['first_name'] . ' ' . $employee['last_name']
                : 'Unknown Trainee';

            $ratings = $group->where('criteria', '!=', 'supervisor_feedback')->mapWithKeys(function ($item) {
                return [$item->criteria => $item->rating];
            });

            $feedbackEntry = $group->firstWhere('criteria', 'supervisor_feedback');
            $feedback = $feedbackEntry ? $feedbackEntry->supervisor_feedback : null;

            return [
                'trainee_id' => $traineeId,
                'full_name' => $fullName,
                'evaluation_date' => optional($group->first()->created_at)->toDateString(),
                'status' => $group->avg('rating') >= 3 ? 'passed' : 'failed',
                'department' => $group->first()->department,
                'supervisor_feedback' => $feedback,
            ] + $ratings->toArray(); // merge ratings into result
        });

        return view('evaluations.results', ['trainees' => $trainees]);
    }
}
