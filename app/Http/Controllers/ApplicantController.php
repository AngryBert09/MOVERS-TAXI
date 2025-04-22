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
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class ApplicantController extends Controller
{
    public function index()
    {
        try {
            // Retrieve all job applications
            $jobApplications = JobApplication::with('jobPosting')
                ->whereIn('status', ['Qualified', 'Not Qualified', 'Pending',])
                ->orderBy('apply_date', 'desc')
                ->get();

            return view('jobs.job-applicants', compact('jobApplications'));
        } catch (\Exception $e) {
            Log::error('Error fetching all job applicants.', ['error' => $e->getMessage()]);
            return redirect()->route('dashboard')->with('error', 'Unable to fetch job applicants.');
        }
    }

    public function onboarding()
    {
        try {
            // Retrieve job applications with status 'Initial' or 'Final'
            $jobApplications = JobApplication::with('jobPosting')
                ->whereIn('status', ['Initial', 'Final', 'Interviewed', 'Examination', 'Requirements', 'Onboarding', 'Failed', 'Scheduled', 'Hired', 'Rejected'])
                ->orderBy('apply_date', 'desc')
                ->get();

            return view('jobs.onboarding', compact('jobApplications'));
        } catch (\Exception $e) {
            Log::error('Error fetching job applicants with Initial or Final status.', ['error' => $e->getMessage()]);
            return redirect()->route('dashboard')->with('error', 'Unable to fetch filtered job applicants.');
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

        // Retrieve current status of the applicant (Initial, Final, or Pending)
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

        // Check if the status is Final, set to Scheduled, and send email
        elseif ($currentStatus === 'Final') {
            Log::info('Status is Final, updating to Scheduled.', ['applicant_id' => $applicant->id]);
            $applicant->status = 'Scheduled';

            // Send email for Scheduled Interview
            try {
                Mail::to($applicant->email)->send(new InterviewScheduledMail(
                    $applicant->name,
                    $request->interview_date,
                    $request->interview_time,
                    'Scheduled Interview' // Pass the status as 'Scheduled Interview'
                ));
                Log::info('Scheduled Interview email sent successfully to:', ['email' => $applicant->email, 'status' => 'Scheduled Interview']);
            } catch (\Exception $e) {
                Log::error('Failed to send Scheduled Interview email', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Failed to send email'], 500);
            }
        }

        // Save the updated status and comply_date
        $applicant->comply_date = $request->interview_date . ' ' . $request->interview_time; // Store the interview date and time in comply_date
        $applicant->save();

        Log::info('Applicant status and comply_date updated:', [
            'applicant_id' => $applicant->id,
            'new_status' => $applicant->status,
            'comply_date' => $applicant->comply_date,
        ]);

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
            'status' => 'required|in:Pending,Hired,Rejected,Interviewed,Initial Interviewed,Final Interviewed,Examination,Requirements,Onboarding,Failed,Passed',
        ]);


        try {
            // Find the job application
            $applicant = JobApplication::findOrFail($request->applicant_id);
            Log::debug('Applicant retrieved successfully.', ['applicant' => $applicant]);

            // Get associated personal information
            $personalInfo = $applicant->personalInformation;
            Log::debug('Personal information retrieved.', ['personal_info' => $personalInfo]);

            // If personal information is not found, return error
            if (!$personalInfo) {
                Log::warning('Personal information not found.', ['applicant_id' => $request->applicant_id]);
                return response()->json(['success' => false, 'message' => 'Personal information not found.'], 404);
            }

            // Get the job posting (to fetch job_title)
            $jobPosting = $applicant->jobPosting; // Assuming JobApplication has a relationship with JobPosting
            Log::debug('Job posting retrieved.', ['job_posting' => $jobPosting]);

            // If job posting is not found, return error
            if (!$jobPosting) {
                Log::warning('Job posting not found.', ['applicant_id' => $request->applicant_id]);
                return response()->json(['success' => false, 'message' => 'Job posting not found.'], 404);
            }

            // Check if the status is 'Initial Interviewed', then update to 'Initial'
            if ($request->status === 'Initial Interviewed') {
                $applicant->status = 'Initial'; // Change status to 'Initial' if it is 'Initial Interviewed'
                Log::debug('Status updated to Initial.', ['applicant_id' => $request->applicant_id]);
            } elseif ($request->status === 'Final Interviewed') {
                $applicant->status = 'Final';
                Log::debug('Status updated to Final.', ['applicant_id' => $request->applicant_id]);
            } elseif ($request->status === 'Pending') {
                $applicant->status = 'Pending';
                Log::debug('Status updated to Pending.', ['applicant_id' => $request->applicant_id]);
            } elseif ($request->status === 'Failed') {
                $applicant->status = 'Failed';
                Log::debug('Status updated to Failed.', ['applicant_id' => $request->applicant_id]);
            } else {
                $applicant->status = $request->status; // Otherwise, set the status to the requested one
                Log::debug('Status updated to custom value.', ['applicant_id' => $request->applicant_id, 'status' => $request->status]);
            }

            $applicant->save();
            Log::info('Status saved successfully.', ['applicant_id' => $request->applicant_id, 'status' => $applicant->status]);

            // Send email notification for Hired or Rejected status
            if (in_array($request->status, ['Hired', 'Rejected'])) {
                try {
                    Mail::to($applicant->email)->send(new ApplicationStatusMail(
                        $applicant->name,
                        $request->status
                    ));
                    Log::info('Status email sent successfully.', ['email' => $applicant->email, 'status' => $request->status]);
                } catch (\Exception $e) {
                    Log::error('Failed to send status email.', ['error' => $e->getMessage()]);
                }
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
                try {
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
                } catch (\Exception $e) {
                    Log::error('Error sending data to external API.', ['error' => $e->getMessage()]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Error updating applicant status.', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error updating applicant status.'], 500);
        }
    }


    public function failApplicant(Request $request)
    {
        Log::info('Fail Applicant Request Received:', $request->all());

        $request->validate([
            'applicant_id' => 'required|exists:job_applications,id',
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $application = JobApplication::findOrFail($request->applicant_id);
            Log::debug('Applicant retrieved successfully.', ['applicant_id' => $application->id]);

            $application->status = 'Failed';
            $application->note = $request->reason;
            $application->save();

            Log::info('Applicant status updated to Failed.', [
                'applicant_id' => $application->id,
                'reason' => $request->reason
            ]);

            return redirect()->back()->with('success', 'Applicant has been marked as failed and reason added.');
        } catch (\Exception $e) {
            Log::error('Error marking applicant as failed.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to mark applicant as failed.');
        }
    }



    //FUNCTION FOR APPLICANTS {START HERE}
    public function applyJob(Request $request)
    {
        try {
            Log::info('Job application request received.', ['request' => $request->all()]);

            // Step 1: Validate input
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'job_posting_id' => 'required|exists:job_postings,id',
                'gender' => 'required|in:Male,Female,Other',
                'birthdate' => 'required|date',
            ]);

            // Use authenticated email
            $validatedData['email'] = auth()->user()->email;

            $userId = auth()->check() ? auth()->id() : null;

            // Step 2: Check if email already exists for the job posting
            $existingApplication = JobApplication::where('email', $validatedData['email'])
                ->where('job_posting_id', $validatedData['job_posting_id'])
                ->first();

            if ($existingApplication) {
                Log::warning('Duplicate application attempt detected.', ['email' => $validatedData['email'], 'job_posting_id' => $validatedData['job_posting_id']]);
                return response()->json([
                    'message' => 'You have already applied for this job. You can only apply once.',
                ], 400);
            }

            // Step 3: Upload resume
            $resumePath = $request->file('resume')->store('resumes', 'public');
            Log::info('Resume uploaded successfully.', ['resume_path' => $resumePath]);

            // Step 4: Split name
            $nameParts = explode(' ', trim($validatedData['name']));
            $firstName = $nameParts[0] ?? '';
            $lastName = count($nameParts) > 2 ? array_pop($nameParts) : '';

            // Step 5: Create job application (initially Pending)
            $jobApplication = JobApplication::create([
                'name' => $validatedData['name'],
                'email' => $userId ? auth()->user()->email : $validatedData['email'],
                'phone' => $validatedData['phone'],
                'apply_date' => now(),
                'status' => 'Pending',
                'resume' => $resumePath,
                'job_posting_id' => $validatedData['job_posting_id'],
                'application_code' => uniqid('APP-'),
            ]);

            Log::info('Job application created successfully.', ['job_application' => $jobApplication]);

            // Step 6: Save personal info
            $personalInfo = PersonalInformation::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
                'birth_date' => $validatedData['birthdate'],
                'application_id' => $jobApplication->id,
            ]);

            Log::info('Personal information saved successfully.', ['personal_info' => $personalInfo]);

            // Step 7: Extract text from resume
            $fullResumePath = storage_path("app/public/{$resumePath}");
            $resumeText = $this->extractTextFromResume($fullResumePath);

            // Step 8: Get job description
            $jobDescription = JobPosting::find($validatedData['job_posting_id'])->description ?? '';

            // Step 9: Send to AI and update status
            $aiResult = $this->sendToGeminiAI($resumeText, $jobDescription, $jobApplication->id);

            if (is_array($aiResult) && isset($aiResult['suitability_score'])) {
                $score = $aiResult['suitability_score'];
                $jobApplication->status = $score > 1 ? 'Qualified' : 'Not Qualified';
                $jobApplication->save();

                Log::info("AI score evaluated. Status updated to: {$jobApplication->status}", ['score' => $score]);
            }

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

    public function applicantFiles()
    {
        return view('jobs.applicant-files');
    }



    private function extractTextFromResume($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        Log::debug("Extracting text from resume. File path: {$filePath}, Extension: {$extension}");

        try {
            if ($extension === 'pdf') {
                Log::debug("Processing PDF file.");
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
                Log::debug("Extracted text from PDF: " . substr($text, 0, 500) . "...");
                return $text ?? 'Could not extract text from PDF.';
            } elseif (in_array($extension, ['doc', 'docx'])) {
                Log::debug("Processing Word document.");
                $phpWord = IOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . " ";
                        }
                    }
                }
                Log::debug("Extracted text from Word document: " . substr($text, 0, 500) . "...");
                return trim($text) ?: 'Could not extract text from Word document.';
            }
        } catch (\Exception $e) {
            Log::error("Error processing file: " . $e->getMessage());
            return "Error processing file: " . $e->getMessage();
        }

        Log::warning("Unsupported file format: {$extension}");
        return 'Unsupported file format.';
    }



    private function sendToGeminiAI($resumeText, $jobDescription)
    {
        $geminiApiKey = env('GEMINI_API_KEY');

        $prompt = <<<EOD
    Analyze the following resume against the job description and respond ONLY with a JSON object between 1 to 10 suitability score. Do not include any explanation, markdown, or formatting.

    {
      "matched_skills": ["..."],
      "missing_qualifications": ["..."],
      "suitability_score": 1
    }

    **Resume:**
    {$resumeText}

    **Job Description:**
    {$jobDescription}
    EOD;

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent", [
                'query' => ['key' => $geminiApiKey],
                'json' => [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            Log::debug('AI Raw Response:', $responseData);

            $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Clean up response if wrapped in markdown
            $cleanedText = trim(preg_replace('/^```json|```$/m', '', $rawText));
            $parsedJson = json_decode(trim($cleanedText), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON from AI:', ['raw' => $cleanedText]);
                return ['suitability_score' => 0]; // fallback to "not qualified"
            }

            Log::info('AI Screening Results:', $parsedJson);

            return $parsedJson;
        } catch (\Exception $e) {
            Log::error('Failed to connect to Gemini AI', ['error' => $e->getMessage()]);
            return ['suitability_score' => 0]; // default to "not qualified" on failure
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
