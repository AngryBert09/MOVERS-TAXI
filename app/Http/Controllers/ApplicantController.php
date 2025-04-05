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
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Http;
use App\Mail\ApplicantCustomMessageMail;


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


    public function scheduleInterview(Request $request)
    {
        // Validate incoming request data
        Log::info('Scheduling Interview Request:', $request->all());

        $request->validate([
            'applicant_id' => 'required|integer',
            'interview_date' => 'required|date',
            'interview_time' => 'required',
        ]);

        // Fetch applicant details
        $applicant = JobApplication::find($request->applicant_id);

        if (!$applicant) {
            Log::error('Applicant not found', ['applicant_id' => $request->applicant_id]);
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        // Log current applicant status
        Log::info('Applicant found, current status:', ['applicant_id' => $applicant->id, 'current_status' => $applicant->status]);

        // Retrieve current status of the applicant (Initial or Final)
        $currentStatus = $applicant->status;

        // Check if the status is Pending, set to Initial Interview, and send email
        if ($currentStatus === 'Pending') {
            Log::info('Status is Pending, setting to Initial Interview.', ['applicant_id' => $applicant->id]);
            $applicant->status = 'Initial';

            // Send email for Initial Interview
            try {
                Mail::to($applicant->email)->send(new InterviewScheduledMail(
                    $applicant->name,
                    $request->interview_date,
                    $request->interview_time,
                    'Initial Interview' // Pass the status as 'Initial Interview'
                ));
                Log::info('Initial Interview email sent successfully to:', ['email' => $applicant->email, 'status' => 'Initial Interview']);
            } catch (\Exception $e) {
                Log::error('Failed to send Initial Interview email', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Failed to send email'], 500);
            }
        }

        // Check if the status is Initial Interview, set to Final Interview, and send email
        elseif ($currentStatus === 'Initial') {
            Log::info('Status is Initial Interview, updating to Final Interview.', ['applicant_id' => $applicant->id]);
            $applicant->status = 'Final';

            // Send email for Final Interview
            try {
                Mail::to($applicant->email)->send(new InterviewScheduledMail(
                    $applicant->name,
                    $request->interview_date,
                    $request->interview_time,
                    'Final Interview' // Pass the status as 'Final Interview'
                ));
                Log::info('Final Interview email sent successfully to:', ['email' => $applicant->email, 'status' => 'Final Interview']);
            } catch (\Exception $e) {
                Log::error('Failed to send Final Interview email', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Failed to send email'], 500);
            }
        }

        // Save the updated status
        $applicant->save();
        Log::info('Applicant status updated:', ['applicant_id' => $applicant->id, 'new_status' => $applicant->status]);

        return response()->json(['success' => 'Interview scheduled, status updated to ' . $applicant->status . ', and email sent successfully.']);
    }

    public function sendMessage(Request $request)
    {
        Log::debug('sendMessage() called', ['request_data' => $request->all()]);

        $request->validate([
            'applicant_id' => 'required|exists:job_applications,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $applicant = JobApplication::findOrFail($request->applicant_id);

        Log::debug('Applicant retrieved', ['applicant' => $applicant]);

        try {
            Mail::to($applicant->email)->send(new ApplicantCustomMessageMail(
                $applicant->name,
                $request->subject,
                $request->message
            ));

            Log::info('Email sent successfully to applicant', [
                'email' => $applicant->email,
                'subject' => $request->subject
            ]);

            return redirect()->back()->with('success', 'Message sent successfully to the applicant!');
        } catch (\Exception $e) {
            Log::error('Error sending applicant message email', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to send the message.');
        }
    }








    // UPDATE STATUS OF APPLICANT
    public function updateStatus(Request $request)
    {
        Log::info('Update Status Request Received:', $request->all());

        // Validate incoming request data
        $request->validate([
            'applicant_id' => 'required|exists:job_applications,id',
            'status' => 'required|in:Pending,Hired,Rejected,Interviewed,Initial Interviewed,Final Interviewed', // Add 'Final Interviewed' to validation
        ]);

        try {
            // Find the job application
            $applicant = JobApplication::findOrFail($request->applicant_id);

            // Get associated personal information
            $personalInfo = $applicant->personalInformation;

            // If personal information is not found, return error
            if (!$personalInfo) {
                return response()->json(['success' => false, 'message' => 'Personal information not found.'], 404);
            }

            // Get the job posting (to fetch job_title)
            $jobPosting = $applicant->jobPosting; // Assuming JobApplication has a relationship with JobPosting

            // If job posting is not found, return error
            if (!$jobPosting) {
                return response()->json(['success' => false, 'message' => 'Job posting not found.'], 404);
            }

            // Check if the status is 'Initial Interviewed', then update to 'Initial'
            if ($request->status === 'Initial Interviewed') {
                $applicant->status = 'Initial'; // Change status to 'Initial' if it is 'Initial Interviewed'
            }
            // Check if the status is 'Final Interviewed', then update to 'Final'
            elseif ($request->status === 'Final Interviewed') {
                $applicant->status = 'Final'; // Change status to 'Final' if it is 'Final Interviewed'
            } else {
                $applicant->status = $request->status; // Otherwise, set the status to the requested one
            }

            $applicant->save();

            Log::info('Status updated successfully.', ['id' => $request->applicant_id, 'status' => $request->status]);

            // Send email notification for Hired or Rejected status
            if (in_array($request->status, ['Hired', 'Rejected'])) {
                Mail::to($applicant->email)->send(new ApplicationStatusMail(
                    $applicant->name,
                    $request->status
                ));
                Log::info('Status email sent successfully.', ['email' => $applicant->email, 'status' => $request->status]);
            }

            // If applicant is Hired, send data to external API
            if ($request->status === 'Hired') {
                // Prepare the data to send to the external API using personal information and job title
                $employeeData = [
                    'first_name' => $personalInfo->first_name,
                    'last_name' => $personalInfo->last_name,
                    'email' => $applicant->email,
                    'department' => $jobPosting->department,
                    'position' => $jobPosting->job_title, // Get job_title from JobPosting model
                    'bdate' => $personalInfo->birth_date,
                    'job_type' => $jobPosting->job_type, // Assuming job_type exists in JobApplication model
                    'gender' => $personalInfo->gender,
                    'status' => 'active', // Assuming "active" status is applicable when hired
                    'contact' => $applicant->phone, // Assuming phone exists in JobApplication model
                ];

                // Log the data that is being sent to the API for debugging purposes
                Log::info('Sending employee data to external API:', ['employee_data' => $employeeData]);

                // Send a POST request to the external API
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('HR1_API_KEY'), // Retrieve HR1 API Key from .env file
                ])->post('https://hr1.moverstaxi.com/api/v1/employees', $employeeData);

                // Log the response from the external API
                Log::info('External API response:', ['response' => $response->body()]);

                if ($response->successful()) {
                    Log::info('Employee data successfully sent to external API.');
                } else {
                    Log::error('Failed to send employee data to external API.', ['response' => $response->body()]);
                }
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
                'gender' => 'required|in:Male,Female,Other',
                'birthdate' => 'required|date',
            ]);

            // Check if the user is authenticated
            $userId = auth()->check() ? auth()->id() : null;

            // Store resume file
            $resumePath = $request->file('resume')->store('resumes', 'public');
            Log::info('Resume uploaded successfully.', ['resume_path' => $resumePath]);

            // Split full name into first and last name

            $nameParts = explode(' ', trim($validatedData['name']));
            $firstName = $nameParts[0] ?? '';
            $lastName = count($nameParts) > 2 ? array_pop($nameParts) : '';


            // Create job application
            $jobApplication = JobApplication::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'apply_date' => now(),
                'status' => 'Pending',
                'resume' => $resumePath,
                'job_posting_id' => $validatedData['job_posting_id'],
                'application_code' => uniqid('APP-'), // Generate unique application code
            ]);

            Log::info('Job application created successfully.', ['job_application' => $jobApplication]);

            // Store gender, birthdate, phone, and application_id in PersonalInformation model
            $personalInfo = PersonalInformation::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $validatedData['phone'], // Storing phone in personal info
                'gender' => $validatedData['gender'],
                'birth_date' => $validatedData['birthdate'],
                'application_id' => $jobApplication->id, // Storing the job application ID
            ]);

            Log::info('Personal information saved successfully.', ['personal_info' => $personalInfo]);

            return response()->json([
                'message' => 'Application submitted successfully!',
                'application_code' => $jobApplication->application_code
            ], 200);
        } catch (ValidationException $e) {
            Log::warning('Validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation failed. Please check the provided details.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while applying for job.', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'A database error occurred. Please try again later.',
                'error_details' => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Unexpected error applying for job.', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong! Please check your input and try again.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }




    public function searchApplication(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query = $request->input('query');

        $application = JobApplication::where('application_code', $query)
            ->orWhere('email', $query)
            ->first();

        if ($application) {
            return response()->json([
                'success' => true,
                'message' => 'Application found!',
                'data' => [
                    'application_code' => $application->application_code,
                    'email' => $application->email,
                    'status' => $application->status,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No application found with the provided details.',
        ], 404);
    }
}
