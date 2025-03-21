<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryReplyMail;

class InquiriesController extends Controller
{
    public function index()
    {
        $inquiries = DB::table('inquiries')->get();
        return view('inquiries.index', compact('inquiries'));
    }

    public function store(Request $request)
    {
        // Validate input fields
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Insert data using raw SQL
        DB::insert(
            'INSERT INTO inquiries (name, email, message, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())',
            [$request->name, $request->email, $request->message]
        );

        // Redirect or return response
        return redirect()->back()->with('success', 'Inquiry submitted successfully.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        $inquiry = DB::table('inquiries')->where('id', $id)->first();

        // Send the email using the Mailable class
        Mail::to($inquiry->email)->send(new InquiryReplyMail($request->reply_message));

        // Delete the inquiry after replying
        DB::table('inquiries')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Reply sent and inquiry deleted successfully.');
    }
}
