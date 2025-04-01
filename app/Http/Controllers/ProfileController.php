<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        // Get the authenticated user and eager load personal information and department
        $user = User::with(['personalInformation', 'department'])->find(Auth::id());

        // Fetch all departments
        $departments = Department::pluck('department_name', 'id');

        // Pass data to the view
        return view('profile.index', compact('user', 'departments'));
    }



    public function update(Request $request)
    {
        try {
            $user = Auth::user(); // Get authenticated user

            // Validate the input data
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'birth_date' => 'nullable|string', // Keep it as a string for now
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'department' => 'nullable|string|max:255',
            ]);

            // Convert birth_date to proper format (YYYY-MM-DD)
            $formattedBirthDate = null;
            if (!empty($request->birth_date)) {
                try {
                    $formattedBirthDate = Carbon::createFromFormat('m/d/Y', $request->birth_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    return back()->with('error', 'Invalid birth date format. Please use MM/DD/YYYY.');
                }
            }

            // Update user personal information
            $user->personalInformation()->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $formattedBirthDate,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);

            // Update user's phone number and department
            $user->update([
                'phone' => $request->phone,
                'department' => $request->department,
            ]);

            // Handle file upload for avatar if exists
            // Handle file upload for avatar if exists
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->personalInformation()->update(['avatar_path' => $avatarPath]);
            }


            session()->flash('success', 'Profile updated successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');
            return redirect()->back();
        }
    }
}
