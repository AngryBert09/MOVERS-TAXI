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
        $analysis = $this->sendToGeminiAI($resumeText, $jobPosting->description, $applicant->id);

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


    private function sendToGeminiAI($resumeText, $jobDescription, $applicantId)
    {
        $geminiApiKey = env('GEMINI_API_KEY');

        $prompt = "Analyze the following resume against the job description and respond with a JSON only make the score 1-10 (no markdown, no explanations):\n\n"
            . "{\n"
            . "  \"matched_skills\": [\"...\"],\n"
            . "  \"missing_qualifications\": [\"...\"],\n"
            . "  \"suitability_score\": 1\n"
            . "}\n\n"
            . "**Resume:**\n{$resumeText}\n\n"
            . "**Job Description:**\n{$jobDescription}";

        $client = new \GuzzleHttp\Client();

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
            Log::debug('AI Raw Response:', $responseData);

            // Extract the raw AI JSON response from within the markdown
            $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $cleanedText = trim(preg_replace('/^```json|```$/m', '', $rawText));
            $parsedJson = json_decode(trim($cleanedText), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON from AI:', ['raw' => $cleanedText]);
                return response()->json(['error' => 'AI returned invalid JSON'], 500);
            }

            // ✨ NEW: Auto-hire logic if score > 5
            if (!empty($parsedJson['suitability_score']) && $parsedJson['suitability_score'] > 5) {
                $applicant = JobApplication::find($applicantId);
                if ($applicant) {
                    $applicant->status = 'Hired';
                    $applicant->save();
                    Log::info("Applicant {$applicant->id} automatically marked as 'hired' by AI.");
                }
            }

            return $parsedJson;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to connect to Gemini AI',
                'message' => $e->getMessage()
            ], 500);
        }
    }




    private function processGeminiResponse($responseJson, $applicantId)
    {
        // Step 1: Extract AI response text
        $rawText = $responseJson['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Step 2: Clean the markdown block (remove ```json ... ```)
        $cleaned = trim(preg_replace('/^```json|```$/m', '', $rawText));

        // Step 3: Decode JSON content
        $data = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON from Gemini', ['content' => $cleaned]);
            return false;
        }

        // Step 4: Get the suitability score
        $score = $data['suitability_score'] ?? null;

        if ($score !== null && $score > 5) {
            // Step 5: Update applicant status to 'hired'
            $applicant = JobApplication::find($applicantId);
            if ($applicant) {
                $applicant->status = 'hired';
                $applicant->save();
                Log::info("Applicant {$applicant->id} has been marked as hired by AI analysis.");
            }
        }

        return $data; // Return the clean parsed result if needed elsewhere
    }
}
