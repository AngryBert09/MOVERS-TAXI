<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle login request
     */

    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Find user by email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            Log::warning("Login attempt failed: Email not found ({$request->email})", ['ip' => $request->ip()]);
            session()->flash('error', 'No account found with this email.');
            return back();
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            Log::warning("Unverified email login attempt: {$user->email}", ['ip' => $request->ip()]);
            session()->flash('error', 'Please verify your email before logging in.');
            return back();
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            Log::warning("Failed login attempt: Incorrect password for ({$request->email})", ['ip' => $request->ip()]);
            session()->flash('error', 'Invalid credentials. Please check your password.');
            return back();
        }

        // Successful login
        $user = Auth::user();
        Log::info("User logged in: {$user->email}", ['ip' => $request->ip()]);

        // Check if user is admin
        if ($user->role !== 'admin') {
            Auth::logout();
            Log::warning("Unauthorized login attempt: {$user->email} (Not admin)", ['ip' => $request->ip()]);
            session()->flash('error', 'Access denied. Admins only.');
            return back();
        }

        // Regenerate session for security
        $request->session()->regenerate();

        Log::info("Admin login successful: {$user->email}");
        return redirect()->route('dashboard')->with('success', 'Welcome Admin!');
    }



    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                Log::info("User logging out: {$user->email}", ['user_id' => $user->id, 'ip' => $request->ip()]);
            } else {
                Log::warning("Logout attempted without authentication", ['ip' => $request->ip()]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info("User logged out successfully", ['ip' => $request->ip()]);

            return redirect()->route('auth.login')->with('success', 'Logged out successfully');
        } catch (\Exception $e) {
            Log::error("Logout failed: " . $e->getMessage(), ['ip' => $request->ip()]);
            return redirect()->route('auth.login')->with('error', 'An error occurred during logout. Please try again.');
        }
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            // Create new user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info("New user registered: {$user->email}", ['user_id' => $user->id, 'ip' => $request->ip()]);

            // Generate verification URL
            $verificationUrl = URL::signedRoute('auth.verify', [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ], Carbon::now()->addMinutes(60)); // Link expires in 60 minutes

            // Send verification email manually
            Mail::send('emails.verify-email', ['url' => $verificationUrl], function ($message) use ($user) {
                $message->to($user->email)->subject('Verify Your Email Address');
            });

            Log::info("Verification email sent to: {$user->email}");

            // Return to registration page with a success message
            return redirect()->route('auth.login')->with('success', 'Registration successful! Please check your email to verify your account.');
        } catch (\Exception $e) {
            Log::error("Registration failed for {$request->email}: " . $e->getMessage(), ['ip' => $request->ip()]);
            return back()->with('error', 'An error occurred during registration. Please try again.');
        }
    }


    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Check if hash matches
        if (!hash_equals(sha1($user->email), $hash)) {
            abort(403, 'Invalid verification link.');
        }

        // Mark email as verified
        if (!$user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
            $user->save();
        }

        return redirect('/login')->with('success', 'Email verified. You can now log in.');
    }
}
