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
use Illuminate\Support\Facades\Session;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Http;


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
            'g-recaptcha-response' => 'required' // Ensure reCAPTCHA response exists
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = '6LfueP0qAAAAAMae8EWZI9ubex1cy505U_ws70UL';
        $recaptchaVerify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ])->json();

        if (!$recaptchaVerify['success']) {
            Log::warning("Failed reCAPTCHA verification for: {$request->email}", ['ip' => $request->ip()]);
            return back()->with('error', 'reCAPTCHA verification failed. Please try again.');
        }

        // Find user by email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            Log::warning("Login attempt failed: Email not found ({$request->email})", ['ip' => $request->ip()]);
            return back()->with('error', 'No account found with this email.');
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            Log::warning("Unverified email login attempt: {$user->email}", ['ip' => $request->ip()]);
            return back()->with('error', 'Please verify your email before logging in.');
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            Log::warning("Failed login attempt: Incorrect password for ({$request->email})", ['ip' => $request->ip()]);
            return back()->with('error', 'Invalid credentials. Please check your password.');
        }

        // Generate and store 2FA code
        $twoFactorCode = rand(1000, 9999); // Generate a 4-digit code
        Session::put('2fa_user_id', $user->id);
        Session::put('2fa_code', $twoFactorCode);
        Session::put('2fa_expiry', now()->addMinutes(3)); // Code expires in 3 minutes

        // Send email with 2FA code
        Mail::to($user->email)->send(new TwoFactorCodeMail($twoFactorCode));

        Log::info("2FA code sent to: {$user->email}", ['ip' => $request->ip()]);

        // Redirect to 2FA verification page
        return redirect()->route('2fa.verify')->with('success', 'A verification code has been sent to your email.');
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
                'role' => 'Applicant',
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


    public function show2faForm()
    {
        if (!Session::has('2fa_user_id')) {
            return redirect()->route('auth.login')->with('error', 'Session expired. Please login again.');
        }

        return view('auth.2fa');
    }
    public function verify2fa(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|digits:4'
        ]);

        if (!Session::has('2fa_user_id') || !Session::has('2fa_code') || !Session::has('2fa_expiry')) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Check if the code has expired
        if (now()->greaterThan(Session::get('2fa_expiry'))) {
            Session::forget(['2fa_user_id', '2fa_code', '2fa_expiry']);
            return redirect()->route('auth.login')->with('error', 'Verification code expired. Please login again.');
        }

        $user = \App\Models\User::find(Session::get('2fa_user_id'));

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Invalid session. Please login again.');
        }

        if ($request->two_factor_code != Session::get('2fa_code')) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        // Clear 2FA session data
        Session::forget(['2fa_user_id', '2fa_code', '2fa_expiry']);

        // Regenerate session for security
        Auth::login($user);
        $request->session()->regenerate();

        Log::info("User 2FA verified: {$user->email}");

        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }


    public function resend2fa()
    {
        if (!Session::has('2fa_user_id')) {
            return redirect()->route('auth.login')->with('error', 'Session expired. Please login again.');
        }

        $user = \App\Models\User::find(Session::get('2fa_user_id'));

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Invalid session. Please login again.');
        }

        // Generate a new 4-digit OTP
        $newOtp = rand(1000, 9999);
        Session::put('2fa_code', $newOtp);
        Session::put('2fa_expiry', now()->addMinutes(3)); // Reset expiration time

        // Send new OTP via email
        Mail::to($user->email)->send(new TwoFactorCodeMail($newOtp));

        Log::info("New 2FA code resent to: {$user->email}");

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
