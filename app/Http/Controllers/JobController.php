<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{
    public function getManageJobs()
    {
        $jobs = JobPosting::all();
        return view('jobs.manage-jobs', compact('jobs'));
    }


    public function getJobVacancies()
    {
        $jobs = JobPosting::where('status', 'Open') // Only show active jobs
            ->orderBy('created_at', 'desc') // Order by latest jobs
            ->get();

        return view('jobs.job-vacancies', compact('jobs'));
    }

    public function showJobDetails($id)
    {
        $job = JobPosting::findOrFail($id);
        return view('jobs.job-view', compact('job'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_title'     => 'required|string|max:255',
            'department'    => 'required|string|max:255',
            'job_location'  => 'required|string|max:255',
            'no_of_vacancies' => 'required|integer|min:1',
            'experience'    => 'required|string|max:255',
            'age'           => 'nullable|integer|min:18',
            'salary_from'   => 'required|numeric|min:0',
            'salary_to'     => 'required|numeric|min:0|gte:salary_from',
            'job_type'      => 'required|string|in:Full Time,Part Time,Internship,Temporary,Remote,Others',
            'status'        => 'required|string|in:Open,Closed,Cancelled',
            'start_date'    => 'required|date',
            'expired_date'  => 'required|date|after:start_date',
            'description'   => 'required|string',
        ]);

        try {
            $job = JobPosting::create($request->all());

            Log::info("New job posted: {$job->job_title}", ['job_id' => $job->id]);
            return redirect()->back()->with('success', 'Job posted successfully!');
        } catch (\Exception $e) {
            Log::error("Job posting failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to post job. Please try again.');
        }
    }
}
