<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\JobApplication;
use Illuminate\Validation\ValidationException;



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


    //FUNCTION FOR APPLICANTS
    public function applyJob(Request $request)
    {
        try {
            Log::info('Job application request received.', ['request' => $request->all()]);

            // Validate request, ensuring the same email cannot be used for the same job posting
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        if (JobApplication::where('email', $value)
                            ->where('job_posting_id', $request->job_posting_id)
                            ->exists()
                        ) {
                            $fail('You have already applied for this job.');
                        }
                    }
                ],
                'phone' => 'required|string|max:15',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'job_posting_id' => 'required|exists:job_postings,id',
            ]);

            // Store resume file
            $resumePath = $request->file('resume')->store('resumes', 'public');
            Log::info('Resume uploaded successfully.', ['resume_path' => $resumePath]);

            // Create job application
            $jobApplication = JobApplication::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'apply_date' => now(),
                'status' => 'Pending',
                'resume' => $resumePath,
                'job_posting_id' => $validatedData['job_posting_id'],
            ]);

            Log::info('Job application created successfully.', ['job_application' => $jobApplication]);

            return response()->json(['message' => 'Application submitted successfully!'], 200);
        } catch (ValidationException $e) {
            // Handle validation errors
            Log::warning('Validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle general errors
            Log::error('Error applying for job.', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong! Please try again later.'
            ], 500);
        }
    }
}
