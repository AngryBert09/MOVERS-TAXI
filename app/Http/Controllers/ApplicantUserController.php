<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplicantFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApplicantUserController extends Controller
{
    public function index()
    {
        $email = Auth::user()->email;
        Log::info('Fetching job applications for user', ['email' => $email]);

        $applications = JobApplication::with('jobPosting')->where('email', $email)->get();

        foreach ($applications as $application) {
            if ($application->status === 'Requirements' && $application->comply_date) {
                $now = now();
                $complyDate = \Carbon\Carbon::parse($application->comply_date);

                // If comply date is in the past
                if ($now->gt($complyDate)) {
                    // Check if it already had an extension (note contains failure hint)
                    if (str_contains(strtolower($application->notes), 'requirements submission failed')) {
                        continue; // Already marked failed
                    }

                    $daysLate = $now->diffInDays($complyDate);

                    if ($daysLate > 21) {
                        // More than 3 weeks passed - mark as failed
                        $application->status = 'Failed';
                        $application->notes = 'Requirements submission failed due to non-compliance within the allowed time frame.';
                        $application->save();
                    } else {
                        // Extend comply date by 3 weeks
                        $application->comply_date = $complyDate->addDays(21);
                        $application->save();
                        Log::info('Comply date extended for application', ['id' => $application->id]);
                    }
                }
            }
        }

        Log::info('Job applications fetched successfully', [
            'email' => $email,
            'application_count' => $applications->count()
        ]);

        return view('portal.dashboard', compact('applications'));
    }


    public function withdraw(Request $request, $id)
    {
        // Find the job application by its ID
        $application = JobApplication::findOrFail($id);

        // Check if the application is not already withdrawn or in a failed state
        if (in_array($application->status, ['Failed', 'Onboarding'])) {
            return redirect()->back()->with('error', 'You cannot withdraw this application.');
        }

        // Delete the application
        $application->delete();

        return redirect()->route('applicant.dashboard')->with('success', 'Application has been withdrawn successfully.');
    }


    public function uploadRequirements(Request $request, $applicationId)
    {
        $request->validate([
            'sss' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'pagibig' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'philhealth' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'tin' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'barangay_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        try {
            $paths = [
                'sss' => $request->file('sss')->store('requirements', 'public'),
                'pagibig' => $request->file('pagibig')->store('requirements', 'public'),
                'philhealth' => $request->file('philhealth')->store('requirements', 'public'),
                'tin' => $request->file('tin')->store('requirements', 'public'),
                'barangay_clearance' => $request->file('barangay_clearance')->store('requirements', 'public'),
            ];

            ApplicantFile::updateOrCreate(
                ['application_id' => $applicationId],
                $paths
            );

            \App\Models\JobApplication::where('id', $applicationId)->update([
                'status' => 'Onboarding'
            ]);

            return back()->with('success', 'Requirements submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading requirements', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while uploading requirements. Please try again.');
        }
    }

    public function evaluate()
    {
        $userId = Auth::id();

        // Check if an evaluation already exists for this user
        $hasSubmitted = DB::table('facility_evaluations')
            ->where('employee_id', $userId)
            ->exists();

        return view('portal.evaluation.facilities', [
            'hasSubmitted' => $hasSubmitted,
            'employeeId' => $userId, // Pass the current user ID
        ]);
    }

    
}
