<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class DocumentController extends Controller
{

    public function index()
    {
        try {
            $userEmail = Auth::user()->email;

            $response = Http::get('https://admin.moverstaxi.com/api/get_user_documents.php', [
                'email' => $userEmail,
            ]);

            if ($response->successful()) {
                $documents = $response->json();

                return view('documents.index', compact('documents'));
            } else {
                return back()->with('error', 'Failed to fetch documents.');
            }
        } catch (\Exception $e) {
            Log::error('Document fetch failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while retrieving documents.');
        }
    }



    public function store(Request $request)
    {
        $request->validate([
            'document_title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        try {
            $response = Http::attach(
                'document',
                file_get_contents($request->file('file')),
                $request->file('file')->getClientOriginalName()
            )->post('https://admin.moverstaxi.com/api/upload_document.php', [
                'email' => Auth::user()->email,
                'name-file' => $request->document_title,
                'department' => 'HR',
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Document uploaded successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to upload document. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Document upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred.');
        }
    }
}
