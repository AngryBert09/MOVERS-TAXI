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
            $trainees = collect($response->json())->filter(function ($employee) {
                return isset($employee['department']) && $employee['department'] === 'HR';
            })->values()->toArray(); // Filter employees with department 'HR'
        }

        // Fetch existing evaluations
        $evaluations = PerformanceEvaluation::pluck('trainee_id')->toArray();

        return view('performance.appraisal', compact('trainees', 'evaluations'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'trainee_id' => 'required', // validate ID exists in trainees table
            'performance' => 'required|array',
        ]);

        $traineeId = $request->input('trainee_id');
        $performance = $request->input('performance');

        // Optional: delete previous performance records if you want to overwrite them
        PerformanceEvaluation::where('trainee_id', $traineeId)
            ->where('category', 'performance')
            ->delete();

        // Save new performance ratings
        foreach ($performance as $criteria => $rating) {
            PerformanceEvaluation::create([
                'trainee_id' => $traineeId,
                'category' => 'performance',
                'criteria' => $criteria,
                'rating' => $rating,
            ]);
        }

        return redirect()->back()->with('success', 'Performance evaluation saved successfully.');
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
                Log::error('Failed to fetch employees', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching employees from API', ['message' => $e->getMessage()]);
        }

        // Group evaluations by trainee_id and pivot
        $trainees = $evaluations->groupBy('trainee_id')->map(function ($group, $traineeId) use ($employees) {
            $employee = $employees->firstWhere('id', $traineeId);
            $fullName = $employee
                ? $employee['first_name'] . ' ' . $employee['last_name']
                : 'Unknown Trainee';

            $ratings = $group->mapWithKeys(function ($item) {
                return [$item->criteria => $item->rating];
            });

            return [
                'trainee_id' => $traineeId,
                'full_name' => $fullName,
                'evaluation_date' => optional($group->first()->created_at)->toDateString(),
                'status' => $group->avg('rating') >= 3 ? 'passed' : 'failed',
            ] + $ratings->toArray(); // merge ratings into result
        });

        return view('performance.results', ['trainees' => $trainees]);
    }
}
