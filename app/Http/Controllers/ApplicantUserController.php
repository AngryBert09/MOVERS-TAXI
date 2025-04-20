<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class ApplicantUserController extends Controller
{
    public function index()
    {
        $email = Auth::user()->email;
        $applications = JobApplication::with('jobPosting')->where('email', $email)->get();

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
}
