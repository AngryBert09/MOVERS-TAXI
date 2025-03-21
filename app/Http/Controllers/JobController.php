<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\JobApplication;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewScheduledMail;
use App\Mail\ApplicationStatusMail;
use App\Models\Department;



class JobController extends Controller
{
    public function getManageJobs()
    {
        $jobs = JobPosting::with('department')->get(); // Eager load departments
        $departments = Department::select('id', 'department_name')->get(); // Select only needed columns

        return view('jobs.manage-jobs', compact('jobs', 'departments'));
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

    // ADD A JOB POSTING
    public function store(Request $request)
    {
        Log::debug('JobController@store: Received request to store a new job.', ['request_data' => $request->all()]);

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
            Log::debug('JobController@store: Validation passed. Creating job posting.');
            $job = JobPosting::create($request->all());

            Log::info("New job posted: {$job->job_title}", ['job_id' => $job->id]);
            return redirect()->back()->with('success', 'Job posted successfully!');
        } catch (\Exception $e) {
            Log::error("JobController@store: Job posting failed.", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to post job. Please try again.');
        }
    }

    // UPDATE JOB POSTING
    public function update(Request $request, $id)
    {
        $request->validate([
            'job_title'     => 'sometimes|string|max:255',
            'department'    => 'sometimes|string|max:255',
            'job_location'  => 'sometimes|string|max:255',
            'no_of_vacancies' => 'sometimes|integer|min:1',
            'experience'    => 'sometimes|string|max:255',
            'age'           => 'sometimes|nullable|integer|min:18',
            'salary_from'   => 'sometimes|numeric|min:0',
            'salary_to'     => 'sometimes|numeric|min:0|gte:salary_from',
            'job_type'      => 'sometimes|string|in:Full Time,Part Time,Internship,Temporary,Remote,Others',
            'status'        => 'sometimes|string|in:Open,Closed,Cancelled',
            'start_date'    => 'sometimes|date',
            'expired_date'  => 'sometimes|date|after:start_date',
            'description'   => 'sometimes|string',
        ]);

        $job = JobPosting::findOrFail($id);
        $job->update($request->only(array_keys($request->all())));

        return redirect()->back()->with('success', 'Job updated successfully!');
    }



    // GET ALL APPLICANTS
    public function getApplicants($jobPostingId)
    {
        try {
            // Retrieve all job applications for a specific job posting
            $jobApplications = JobApplication::with('jobPosting')
                ->where('job_posting_id', $jobPostingId)
                ->orderBy('apply_date', 'desc')
                ->get();

            // Get the job posting details
            $jobPosting = JobPosting::findOrFail($jobPostingId);

            return view('jobs.job-applicants', compact('jobApplications', 'jobPosting'));
        } catch (\Exception $e) {
            Log::error('Error fetching job applicants.', ['error' => $e->getMessage()]);
            return redirect()->route('job-postings.index')->with('error', 'Job posting not found.');
        }
    }

    public function destroy($id)
    {
        try {
            $job = JobPosting::findOrFail($id); // Find the job or throw a 404

            // Delete all related job applications
            $applicantsDeleted = $job->applications()->delete(); // Assuming a hasMany relationship

            // Now delete the job posting itself
            $job->delete();

            Log::info("Job and related applicants deleted successfully", [
                'job_id' => $id,
                'applicants_deleted' => $applicantsDeleted
            ]);

            return redirect()->back()->with('success', 'Job and its applicants deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Error deleting job and its applicants", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete job. Please try again.');
        }
    }
}
