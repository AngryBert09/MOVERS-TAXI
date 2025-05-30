<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerformanceEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class EvaluationController extends Controller
{


    public function index()
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

                return response()->json([
                    'message' => 'Failed to retrieve employee data from external API.'
                ], 502); // Bad Gateway
            }
        } catch (\Exception $e) {
            Log::error('Error fetching employees from API', ['message' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error fetching employee data.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }

        // Group evaluations by trainee_id and structure them
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
                'employee_id' => $traineeId,
                'full_name' => $fullName,
                'evaluation_date' => optional($group->first()->created_at)->toDateString(),
                'department' => $group->first()->department,
                'status' => $group->avg('rating') >= 3 ? 'passed' : 'failed',
                'supervisor_feedback' => $feedback,
            ] + $ratings->toArray(); // Merge ratings into the response
        })->values(); // Reset keys for JSON array

        return response()->json([
            'message' => 'Performance evaluations retrieved successfully.',
            'data' => $trainees
        ]);
    }


    public function store(Request $request)
    {
        $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
        $apiToken = env('HR1_API_KEY');

        // Fetch employee data from external API
        $employeeResponse = Http::withToken($apiToken)->get($apiUrl);

        if (!$employeeResponse->successful()) {
            return response()->json([
                'message' => 'Unable to validate employee ID. External API is unreachable.',
                'api_status' => $employeeResponse->status()
            ], 502); // Bad Gateway
        }

        $employees = collect($employeeResponse->json());
        $employeeIds = $employees->pluck('id')->toArray();

        // Define validation rules
        $rules = [
            'employee_id' => [
                'required',
                function ($attribute, $value, $fail) use ($employeeIds) {
                    if (!in_array($value, $employeeIds)) {
                        $fail('The selected employee ID does not exist in the employee database.');
                    }
                },
            ],
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
            'performance.*' => 'required|integer|between:1,5',
            'supervisor_feedback' => 'nullable|string|max:2000',
        ];

        $messages = [
            'performance.*.required' => 'All performance ratings must be provided.',
            'performance.*.between' => 'Ratings must be between 1 and 5.',
        ];

        // Validate the request
        try {
            $validated = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        }

        // Check for null values in performance ratings
        if (in_array(null, $validated['performance'], true)) {
            return response()->json([
                'message' => 'All performance ratings must be provided.'
            ], 400);
        }

        // Get department from API
        $employee = $employees->firstWhere('id', $validated['employee_id']);

        if (!$employee || empty($employee['department'])) {
            return response()->json([
                'message' => 'Department information not found for this employee.'
            ], 400);
        }

        $departmentFromApi = $employee['department'];

        // Begin DB transaction
        DB::beginTransaction();

        try {
            // Store performance ratings
            foreach ($validated['performance'] as $criteria => $rating) {
                PerformanceEvaluation::create([
                    'employee_id' => $validated['employee_id'],
                    'department' => $departmentFromApi,
                    'category' => 'performance',
                    'criteria' => $criteria,
                    'rating' => $rating,
                ]);
            }

            // Store supervisor feedback if available
            if (!empty($validated['supervisor_feedback'])) {
                PerformanceEvaluation::updateOrCreate(
                    [
                        'employee_id' => $validated['employee_id'],
                        'category' => 'feedback',
                        'criteria' => 'supervisor_feedback',
                        'department' => $departmentFromApi,
                    ],
                    [
                        'rating' => 0,
                        'supervisor_feedback' => $validated['supervisor_feedback'],
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Performance evaluation saved successfully.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to save evaluation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
