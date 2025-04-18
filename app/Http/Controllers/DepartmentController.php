<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TrainingAchievement;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('departments.index', compact('departments'));
    }

    public function show($name)
    {
        try {
            $token = env('HR1_API_KEY');

            $response = Http::withToken($token)->get('https://hr1.moverstaxi.com/api/v1/employees');

            if ($response->successful()) {
                $employees = collect($response->json()) // direct array, not inside 'data'
                    ->filter(fn($emp) => isset($emp['department']) && $emp['department'] === $name)
                    ->values() // re-index collection
                    ->map(function ($employee) {
                        // Get achievements from the database using the employee's ID
                        $achievements = TrainingAchievement::where('employee_id', $employee['id'])->get();

                        // Add achievements to the employee
                        $employee['achievements'] = $achievements;

                        return $employee; // return the modified employee
                    });

                return view('employee.index', [
                    'departmentName' => $name,
                    'employees' => $employees
                ]);
            }

            return back()->withErrors(['api_error' => 'Failed to fetch employees from HR API.']);
        } catch (\Exception $e) {
            return back()->withErrors(['exception' => 'An error occurred: ' . $e->getMessage()]);
        }
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

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->back()->with('success', 'Department deleted successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,department_name,' . $id,
        ]);

        $department = Department::findOrFail($id);
        $department->update([
            'department_name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Department updated successfully!');
    }
}
