<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\TrainingType;
use App\Models\Trainer;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\TrainingAchievement;

class TrainingController extends Controller
{
    public function getTrainingList()
    {
        $trainings = Training::with(['trainer', 'trainingType'])->get();
        $trainingTypes = TrainingType::all();
        $trainers = Trainer::where('status', 'Active')->get();

        // Fetch employees from external API
        $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
        $apiToken = env('HR1_API_KEY'); // Store token in .env

        try {
            $response = Http::withToken($apiToken)->get($apiUrl);

            if ($response->successful()) {
                $employees = $response->json(); // Convert response to an array
            } else {
                Log::error('Failed to fetch employees', ['status' => $response->status(), 'body' => $response->body()]);
                $employees = []; // Fallback to empty if API fails
            }
        } catch (\Exception $e) {
            Log::error('Error fetching employees from API', ['message' => $e->getMessage()]);
            $employees = [];
        }

        return view('trainings.training-list', compact('trainings', 'trainingTypes', 'trainers', 'employees'));
    }

    public function storeTraining(Request $request)
    {
        Log::info('Store Training Request Received', ['request' => $request->all()]);

        try {
            // Fetch employees from API
            $response = Http::withToken(env('HR1_API_KEY'))
                ->get('https://hr1.moverstaxi.com/api/v1/employees');

            if ($response->failed()) {
                Log::error('Failed to fetch employees from API', ['response' => $response->body()]);
                return redirect()->back()->with('error', 'Failed to fetch employees. Please try again.');
            }

            $employees = $response->json();
            $employeeIds = collect($employees)->pluck('id')->toArray(); // Extract IDs

            // Validate request
            $validatedData = $request->validate([
                'training_type' => 'required|exists:training_types,type_name',
                'trainer' => 'required|string',
                'trainee_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($employeeIds) {
                        if (!in_array($value, $employeeIds)) {
                            $fail('The selected trainee is not a valid employee.');
                        }
                        if (Training::where('trainee_id', $value)->exists()) {
                            $fail('The selected trainee is already assigned to a training.');
                        }
                    }
                ],
                'training_cost' => 'required|numeric|min:0',
                'start_date' => 'required|date_format:d/m/Y',
                'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
                'description' => 'required|string',
                'status' => 'required|in:Active,Inactive',
            ]);

            Log::info('Validation Passed', ['validated_data' => $validatedData]);

            // Convert date format to YYYY-MM-DD
            $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

            Log::info('Date Conversion Completed', [
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date']
            ]);

            // Store the training record
            $training = Training::create($validatedData);

            Log::info('Training Record Created', ['training' => $training]);

            return redirect()->back()->with('success', 'Training added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected Error', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while adding the training. Please try again.');
        }
    }


    public function updateTraining(Request $request, $id)
    {
        Log::info('Update Training Request Received', ['training_id' => $id, 'request' => $request->all()]);

        try {
            // API setup for fetching employees
            $apiUrl = env('EMPLOYEE_API_URL', 'https://hr1.moverstaxi.com/api/v1/employees');
            $apiToken = env('HR1_API_KEY'); // Store token in .env

            $response = Http::withToken($apiToken)->get($apiUrl);
            if ($response->successful()) {
                $employees = collect($response->json()); // Convert API response to a collection
            } else {
                Log::error('Failed to fetch employees from API');
                return redirect()->back()->with('error', 'Failed to retrieve employees. Please try again.');
            }

            // Validate request
            $validatedData = $request->validate([
                'training_type' => 'required|exists:training_types,type_name',
                'trainer' => 'required|string',
                'trainee_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($employees, $id) {
                        // Check if trainee exists in API response
                        if (!$employees->contains('id', $value)) {
                            $fail('The selected trainee does not exist.');
                        }

                        // Ensure trainee isn't already assigned to another training
                        $exists = Training::where('trainee_id', $value)->where('id', '!=', $id)->exists();
                        if ($exists) {
                            $fail('The selected trainee is already assigned to another training.');
                        }
                    }
                ],
                'training_cost' => 'required|numeric|min:0',
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
                'description' => 'sometimes|required|string',
                'status' => 'sometimes|required|in:Active,Inactive,Completed',
            ]);

            Log::info('Validation Passed', ['validated_data' => $validatedData]);

            // Find and update the training record
            $training = Training::findOrFail($id);
            $training->update($validatedData);

            Log::info('Training Record Updated', ['updated_training' => $training]);

            // If the training status is completed, store in the training_achievements table
            if ($training->status == 'Completed') {


                // Store the training achievement details
                $trainingAchievement = TrainingAchievement::create([
                    'employee_id' => $training->trainee_id,  // Trainee's employee ID
                    'type' => $training->training_type,  // Training type
                    'training_date' => $training->end_date,  // End date of training
                    'training_provider' => $training->trainer,  // Training provider
                    'status' => 'Completed',  // Status of the training
                ]);

                Log::info('Training Achievement Created', ['training_achievement' => $trainingAchievement]);
            }

            return redirect()->back()->with('success', 'Training updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected Error', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while updating the training. Please try again.');
        }
    }



    public function destroyTraining($id)
    {
        Log::info('Delete Training Request Received', ['training_id' => $id]);

        try {
            // Find the training record
            $training = Training::findOrFail($id);

            // Delete the training record
            $training->delete();

            Log::info('Training Record Deleted', ['deleted_training_id' => $id]);

            return redirect()->back()->with('success', 'Training deleted successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Training Not Found', ['training_id' => $id]);
            return redirect()->back()->with('error', 'Training record not found.');
        } catch (\Exception $e) {
            Log::error('Unexpected Error While Deleting', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong while deleting the training. Please try again.');
        }
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
