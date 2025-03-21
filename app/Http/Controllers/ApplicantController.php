<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewScheduledMail;
use App\Mail\ApplicationStatusMail;
use Illuminate\Validation\ValidationException;

class ApplicantController extends Controller
{
    public function index()
    {
        try {
            // Retrieve all job applications
            $jobApplications = JobApplication::with('jobPosting')
                ->orderBy('apply_date', 'desc')
                ->get();

            return view('jobs.job-applicants', compact('jobApplications'));
        } catch (\Exception $e) {
            Log::error('Error fetching all job applicants.', ['error' => $e->getMessage()]);
            return redirect()->route('dashboard')->with('error', 'Unable to fetch job applicants.');
        }
    }


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

    //SCHED APPLICANT INTERVIEW
    public function scheduleInterview(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|integer',
            'interview_date' => 'required|date',
            'interview_time' => 'required',
        ]);

        // Fetch applicant details
        $applicant = JobApplication::find($request->applicant_id);

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        // Send email to the applicant
        Mail::to($applicant->email)->send(new InterviewScheduledMail(
            $applicant->name,
            $request->interview_date,
            $request->interview_time
        ));

        return response()->json(['success' => 'Interview scheduled and email sent successfully.']);
    }

    // UPDATE STATUS OF APPLICANT
    public function updateStatus(Request $request)
    {
        Log::info('Update Status Request Received:', $request->all());

        $request->validate([
            'applicant_id' => 'required|exists:job_applications,id',
            'status' => 'required|in:Pending,Hired,Rejected,Interviewed',
        ]);

        try {
            $applicant = JobApplication::findOrFail($request->applicant_id);
            $applicant->status = $request->status;
            $applicant->save();

            Log::info('Status updated successfully.', ['id' => $request->applicant_id, 'status' => $request->status]);

            // Send email only if Hired or Rejected
            if (in_array($request->status, ['Hired', 'Rejected'])) {
                Mail::to($applicant->email)->send(new ApplicationStatusMail(
                    $applicant->name,
                    $request->status
                ));
                Log::info('Status email sent successfully.', ['email' => $applicant->email, 'status' => $request->status]);
            }

            return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Error updating applicant status', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error updating applicant status.'], 500);
        }
    }


    //FUNCTION FOR APPLICANTS {START HERE}
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
