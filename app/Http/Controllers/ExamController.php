<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JobApplication;

class ExamController extends Controller
{
    public function index()
    {
        // Retrieve all questions from the database
        $questions = DB::table('questions')->get();

        // Pass the questions to the view
        return view('exams.index', compact('questions'));
    }



    public function store(Request $request)
    {
        // Validate the form inputs first
        $request->validate([
            'category'       => 'required|string|max:100',
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        // Insert into database using DB facade
        DB::table('questions')->insert([
            'category'       => $request->category,
            'question'       => $request->question,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // Redirect to exams.index view with success message
        return redirect()->route('examinations')->with('success', 'Question added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        $question = DB::table('questions')->where('id', $id)->first();

        if (!$question) {
            return redirect()->back()->with('error', 'Question not found!');
        }

        DB::table('questions')->where('id', $id)->update([
            'category' => $request->category,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Question updated successfully!');
    }

    public function destroy($id)
    {
        $question = DB::table('questions')->where('id', $id)->first();

        if (!$question) {
            return redirect()->back()->with('error', 'Question not found!');
        }

        DB::table('questions')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Question deleted successfully!');
    }

    public function submitExam(Request $request, $applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);

        $answers = $request->input('answers'); // user submitted answers
        $totalQuestions = count($answers);
        $correctAnswers = 0;

        foreach ($answers as $questionId => $userAnswer) {
            $question = DB::table('questions')->where('id', $questionId)->first();
            if ($question) {
                if ($question->correct_answer == $userAnswer) {
                    $correctAnswers++;
                }
            }
        }

        $scorePercentage = ($correctAnswers / $totalQuestions) * 100;

        // Save the exam result using DB::table
        DB::table('exam_results')->insert([
            'job_application_id' => $application->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'score_percentage' => $scorePercentage,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('applicant.dashboard')->with('success', 'Exam submitted successfully!');
    }
}
