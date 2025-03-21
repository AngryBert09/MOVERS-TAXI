<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\TrainingType;
use App\Models\Trainer;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;

class TrainingController extends Controller
{
    public function getTrainingList()
    {
        $trainings = Training::with(['trainer', 'trainingType'])->get();
        $trainingTypes = TrainingType::all();
        $trainers = Trainer::where('status', 'Active')->get();

        // Fetch only employees who have been hired
        $employees = JobApplication::where('status', 'Hired')->get();

        return view('trainings.training-list', compact('trainings', 'trainingTypes', 'trainers', 'employees'));
    }

    public function storeTraining(Request $request)
    {
        Log::info('Store Training Request Received', ['request' => $request->all()]);

        $request->validate([
            'training_type' => 'required|exists:training_types,type_name',
            'trainer' => 'required|string',
            'trainee_id' => 'required|exists:job_applications,id',
            'training_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
            'description' => 'required|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Log::info('Validation Passed', ['validated_data' => $request->all()]);

        // Convert date format to YYYY-MM-DD for database storage
        $start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        Log::info('Date Conversion Completed', ['start_date' => $start_date, 'end_date' => $end_date]);

        // Store the training record
        $training = Training::create([
            'training_type' => $request->training_type,
            'trainer' => $request->trainer,
            'trainee_id' => $request->trainee_id,
            'training_cost' => $request->training_cost,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        Log::info('Training Record Created', ['training' => $training]);

        return redirect()->back()->with('success', 'Training added successfully!');
    }







    // FUNCTIONS FOR TRAINERS
    public function getTrainers()
    {
        $trainers = Trainer::all(); // Fetch all trainers
        return view('trainings.trainers', compact('trainers'));
    }

    public function storeTrainer(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Active,Inactive',
            'description' => 'required|string',
        ]);

        $trainer = new Trainer();
        $trainer->first_name = $request->first_name;
        $trainer->last_name = $request->last_name;
        $trainer->role = $request->role;
        $trainer->email = $request->email;
        $trainer->phone = $request->phone;
        $trainer->status = $request->status;
        $trainer->description = $request->description;
        $trainer->save();

        return redirect()->back()->with('success', 'Trainer added successfully!');
    }

    public function updateTrainer(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Active,Inactive',
            'description' => 'required|string',
        ]);

        $trainer = Trainer::findOrFail($id);
        $trainer->first_name = $request->first_name;
        $trainer->last_name = $request->last_name;
        $trainer->role = $request->role;
        $trainer->email = $request->email;
        $trainer->phone = $request->phone;
        $trainer->status = $request->status;
        $trainer->description = $request->description;
        $trainer->save();

        return redirect()->back()->with('success', 'Trainer updated successfully!');
    }


    public function destroyTrainer($id)
    {
        // Find the trainer by ID
        $trainer = Trainer::findOrFail($id);

        // Delete the trainer
        $trainer->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Trainer deleted successfully.');
    }







    // FUNCTIONS FOR TRAINING TYPES
    public function getTrainingTypes()
    {
        $trainingTypes = TrainingType::all(); // Fetch all training types
        return view('trainings.training-type',  compact('trainingTypes'));
    }

    public function storeTrainingType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:training_types,type_name',
            'description' => 'required|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        TrainingType::create([
            'type_name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Training Type added successfully!');
    }

    public function updateTrainingType(Request $request, $id)
    {
        $trainingType = TrainingType::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:training_types,type_name,' . $id,
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:Active,Inactive',
        ]);

        $trainingType->update($request->only(['name', 'description', 'status']));

        return redirect()->back()->with('success', 'Training Type updated successfully!');
    }

    public function destroyTrainingType($id)
    {
        $trainingType = TrainingType::findOrFail($id);
        $trainingType->delete();

        return redirect()->back()->with('success', 'Training Type deleted successfully!');
    }
}
