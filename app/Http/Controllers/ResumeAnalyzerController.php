<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Parser;
use PhpOffice\PhpWord\IOFactory;
use App\Models\JobApplication;
use App\Models\JobPosting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Facades\Log;

class ResumeAnalyzerController extends Controller
{
    public function analyzeResume(Request $request)
    {
        $applicant = JobApplication::find($request->applicant_id);
        if (!$applicant || !$applicant->resume) {
            return response()->json(['error' => 'Resume not found.'], 404);
        }

        $resumeText = $this->extractTextFromResume(storage_path("app/public/{$applicant->resume}"));

        $jobPosting = JobPosting::find($applicant->job_posting_id);
        if (!$jobPosting) {
            return response()->json(['error' => 'Job Posting not found.'], 404);
        }

        // AI Analysis using Gemini 2.0 Flash
        $analysis = $this->sendToGeminiAI($resumeText, $jobPosting->description);

        return response()->json(['analysis' => $analysis]);
    }


    private function extractTextFromResume($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        Log::debug("Extracting text from resume. File path: {$filePath}, Extension: {$extension}");

        try {
            if ($extension === 'pdf') {
                Log::debug("Processing PDF file.");
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
                Log::debug("Extracted text from PDF: " . substr($text, 0, 500) . "...");
                return $text ?? 'Could not extract text from PDF.';
            } elseif (in_array($extension, ['doc', 'docx'])) {
                Log::debug("Processing Word document.");
                $phpWord = IOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . " ";
                        }
                    }
                }
                Log::debug("Extracted text from Word document: " . substr($text, 0, 500) . "...");
                return trim($text) ?: 'Could not extract text from Word document.';
            }
        } catch (\Exception $e) {
            Log::error("Error processing file: " . $e->getMessage());
            return "Error processing file: " . $e->getMessage();
        }

        Log::warning("Unsupported file format: {$extension}");
        return 'Unsupported file format.';
    }

    private function sendToGeminiAI($resumeText, $jobDescription)
    {
        $geminiApiKey = env('GEMINI_API_KEY');

        $prompt = "Analyze the following resume against the job description and provide insights:\n\n"
            . "**Resume Content:**\n{$resumeText}\n\n"
            . "**Job Description:**\n{$jobDescription}\n\n"
            . "### Required Analysis:\n"
            . "1. **Key Skills Match** - List skills in the resume that match the job description.\n"
            . "2. **Missing Qualifications** - List any missing skills, experience, or qualifications required for the job.\n"
            . "3. **Overall Suitability Score (1-10)** - Provide a score based on how well the candidate fits the job requirements.\n";

        $client = new Client();

        try {
            $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent", [
                'query' => ['key' => $geminiApiKey],
                'json' => [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Extract actual analysis from the response
            return $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from AI';
        } catch (RequestException $e) {
            return [
                'error' => 'Failed to connect to Gemini AI.',
                'message' => $e->getMessage(),
            ];
        }
    }
}
