<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // Get the authenticated user and eager load their personal information
        $user = User::with('personalInformation')->find(Auth::id());

        // Pass the user (along with their personal information) to the view
        return view('profile.index', compact('user'));
    }


    public function update(Request $request)
    {
        try {
            $user = Auth::user(); // Get authenticated user

            // Validate the input data
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'department' => 'nullable|string|max:255',
            ]);

            // Update user personal information
            $user->personalInformation()->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);

            // Update user's phone number and department
            $user->update([
                'phone' => $request->phone,
                'department' => $request->department,
            ]);

            // Handle file upload for avatar if exists
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->update(['avatar' => $avatarPath]);
            }

            // Store success message in session
            session()->flash('success', 'Profile updated successfully!');

            return redirect()->back(); // Redirect back to the previous page

        } catch (\Exception $e) {
            // Store error message in session
            session()->flash('error', 'An error occurred while updating the profile. Please try again.');

            return redirect()->back(); // Redirect back to the previous page
        }
    }
}
