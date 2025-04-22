<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicantFile;

class ApplicantFileController extends Controller
{
    public function index()
    {
        $applicantFiles = ApplicantFile::get();
        return view('jobs.applicant-files', compact('applicantFiles'));
    }
}
