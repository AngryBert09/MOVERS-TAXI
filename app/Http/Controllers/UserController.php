<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::all();
        return view('users.index', compact('users'));
    }



    public function store(Request $request)
    {
        // Validate the form input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:Admin,Applicant',
        ]);

        try {
            // Create the User
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Store Personal Information
            PersonalInformation::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            // Generate verification URL
            $verificationUrl = URL::signedRoute('auth.verify', [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ], Carbon::now()->addMinutes(60)); // Expires in 60 minutes

            // Send verification email
            Mail::send('emails.verify-email', ['url' => $verificationUrl], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Verify Your Email Address');
            });

            return redirect()->back()->with('success', 'User account created successfully! Please check your email to verify the account.');
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the user.');
        }
    }

    public function update(Request $request, $id)
    {
        // Log the incoming request data
        Log::debug('Update user request received', ['id' => $id, 'data' => $request->all()]);

        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id,
            'password'   => 'nullable|min:6|confirmed',
            'role'       => 'required|in:Admin,Applicant',
            'status'     => 'required|in:Active,Inactive',
        ]);

        try {
            // Find the user
            $user = User::findOrFail($id);
            Log::debug('User found', ['user' => $user]);

            // Update user fields
            $user->name  = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->role  = $request->role;
            $user->status = $request->status;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                Log::debug('Password updated for user', ['id' => $id]);
            }

            $user->save();
            Log::debug('User updated successfully', ['user' => $user]);

            // Update personal information
            $personal = $user->personalInformation;
            if ($personal) {
                $personal->first_name = $request->first_name;
                $personal->last_name  = $request->last_name;
                $personal->save();
                Log::debug('Personal information updated', ['personal' => $personal]);
            }

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('User update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while updating the user.');
        }
    }

    public function search(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10); // optional: add ->appends($request->query()) for pagination with filters

        return view('users.index', compact('users'));
    }
}
