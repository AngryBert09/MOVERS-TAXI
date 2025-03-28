<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    // GET All Job Applications
    public function index()
    {
        $applications = JobApplication::with('jobPosting')->get();
        return response()->json($applications);
    }

    // GET Single Application
    public function show($id)
    {
        $application = JobApplication::with('jobPosting')->find($id);
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }
        return response()->json($application);
    }

    // POST Create Job Application
    public function store(Request $request)
    {
        $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'name' => 'required|string',
            'email' => 'required|email|unique:job_applications,email',
            'phone' => 'required|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        // Handle resume upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = JobApplication::create([
            'job_posting_id' => $request->job_posting_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'resume' => $resumePath,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Application submitted successfully', 'data' => $application], 201);
    }

    // PUT Update Job Application Status
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,rejected,hired'
        ]);

        $application = JobApplication::find($id);
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $application->update(['status' => $request->status]);

        return response()->json(['message' => 'Application status updated', 'data' => $application]);
    }

    // DELETE Job Application
    public function destroy($id)
    {
        $application = JobApplication::find($id);
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $application->delete();
        return response()->json(['message' => 'Application deleted successfully']);
    }
}
