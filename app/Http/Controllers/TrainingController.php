<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\TrainingType;
use App\Models\Trainer;

class TrainingController extends Controller
{
    public function getTrainingList()
    {
        $trainings = Training::with(['trainer', 'trainingType', 'employee'])->get();
        $trainingTypes = TrainingType::all();
        $trainers = Trainer::where('status', 'Active')->get();
        // $employees = Employee::all();

        return view('trainings.training-list', compact('trainings', 'trainingTypes', 'trainers'));
    }




    public function storeTraining(Request $request)
    {
        $request->validate([
            'training_type_id' => 'required|exists:training_types,id',
            'trainer_id' => 'required|exists:trainers,id',
            'employee_id' => 'required|exists:employees,id',
            'training_cost' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'required|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Training::create($request->all());

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
