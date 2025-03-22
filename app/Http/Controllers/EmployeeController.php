<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = JobApplication::where('status', 'Hired')->with('jobPosting')->get();
        return view('employee.new-hired', compact('employees'));
    }
}
