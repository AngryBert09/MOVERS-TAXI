<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,department_name',
        ]);

        Department::create([
            'department_name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Department added successfully!');
    }
}
