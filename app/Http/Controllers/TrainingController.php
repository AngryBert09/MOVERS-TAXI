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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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
            // Fetch employees from API with timeout
            $response = Http::withToken(env('HR1_API_KEY'))
                ->timeout(10)
                ->get('https://hr1.moverstaxi.com/api/v1/employees');

            if ($response->failed()) {
                Log::error('Failed to fetch employees from API', ['response' => $response->body()]);
                return redirect()->back()->with('error', 'Failed to fetch employees. Please try again.');
            }

            $employeeIds = collect($response->json())->pluck('id')->toArray();

            // Validate request
            $validatedData = $request->validate([
                'training_type' => 'required|exists:training_types,type_name',
                'trainer' => 'required|string',
                'trainee_id' => [
                    'required',
                    Rule::in($employeeIds),
                    function ($attribute, $value, $fail) {
                        if (Training::where('trainee_id', $value)->where('status', 'Active')->exists()) {
                            $fail('The selected trainee is already assigned to an active training.');
                        }
                    }
                ],
                'training_cost' => 'required|numeric|min:0',
                'start_date' => 'required|date_format:d/m/Y',
                'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
                'description' => 'required|string',
            ]);

            // Convert date format
            $validatedData['start_date'] = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $validatedData['end_date'] = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

            // Budget Validation (consider adding fiscal year filtering)
            $totalApprovedBudget = DB::table('budget_requests')
                ->where('status', 'Approved')
                ->sum('amount');

            $totalTrainingCost = Training::sum('training_cost');
            $remainingBudget = $totalApprovedBudget - $totalTrainingCost;

            if ($remainingBudget < $validatedData['training_cost']) {
                return redirect()->back()
                    ->with('error', 'Insufficient approved budget. Remaining budget: ' . number_format($remainingBudget, 2))
                    ->withInput();
            }

            // Store the training record
            Training::create($validatedData);

            return redirect()->back()->with('success', 'Training added successfully!');
        } catch (\Exception $e) {
            Log::error('Training Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong while adding the training.')
                ->withInput();
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
                'status' => 'sometimes|required|in:Pending,Ongoing,Completed',
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




    // FUNCTION FOR TRAINING EMPLOYEE
    public function getEmployees()
    {
        return view('trainings.for-training');
    }






    // FUNCTIONS FOR TRAINERS
    public function getTrainers()
    {
        // Fetch all trainers from the local DB
        $trainers = Trainer::all();

        // Fetch employees from HR1 API
        $token = env('HR1_API_KEY');
        $employees = collect(); // Default empty collection

        try {
            $response = Http::withToken($token)->get('https://hr1.moverstaxi.com/api/v1/employees');

            if ($response->successful()) {
                $employees = collect($response->json());

                // Filter out employees who are already assigned as trainers
                $assignedTrainerIds = Trainer::pluck('employee_id')->toArray();
                $employees = $employees->filter(function ($employee) use ($assignedTrainerIds) {
                    return !in_array($employee['id'], $assignedTrainerIds);
                });
            } else {
                Log::error('Failed to fetch employees', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('API Error:', ['message' => $e->getMessage()]);
        }

        return view('trainings.trainers', compact('trainers', 'employees'));
    }


    public function storeTrainer(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'trainer_id' => 'required', // Ensure trainer_id exists in employees
            'status' => 'required|in:Active,Inactive',
            'description' => 'required|string',
            'role' => 'nullable|string', // Role is optional if you want to allow customization
        ]);

        // Fetch employee data from HR API based on the selected trainer_id
        $token = env('HR1_API_KEY');
        $response = Http::withToken($token)->get("https://hr1.moverstaxi.com/api/v1/employees/{$request->trainer_id}");

        if ($response->successful()) {
            $employee = $response->json(); // Get employee details from the API response

            // Create the new trainer with data from the API and the request
            $trainer = new Trainer();
            $trainer->employee_id = $employee['id']; // Store the employee_id
            $trainer->first_name = $employee['first_name'];
            $trainer->last_name = $employee['last_name'];

            // Set the role from the request or default to 'Trainer' if not provided
            $trainer->role = $request->role ?? 'Trainer'; // Default to 'Trainer' if no role is specified

            $trainer->email = $employee['email']; // Assuming the employee object contains 'email'
            $trainer->phone = $employee['contact'] ?? null; // Assuming 'phone' might be null
            $trainer->status = $request->status;
            $trainer->description = $request->description;
            $trainer->save();

            return redirect()->back()->with('success', 'Trainer added successfully!');
        } else {
            // Handle the error if fetching from API fails
            return redirect()->back()->with('error', 'Failed to fetch employee details from HR API');
        }
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
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255|unique:training_types,type_name',
            'description' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'contract_attachment' => 'nullable|mimes:pdf,doc,docx|max:10240', // Validate file upload (optional field)
        ], [
            'name.required' => 'The training type name is required.',
            'name.unique' => 'The training type name must be unique.',
            'description.required' => 'The description is required.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either Active or Inactive.',
            'contract_attachment.mimes' => 'The contract attachment must be a file of type: pdf, doc, docx.',
            'contract_attachment.max' => 'The contract attachment may not be greater than 10MB.',
        ]);

        // Handle the file upload if there is a contract attachment
        $contractPath = null;
        if ($request->hasFile('contract_attachment')) {
            $file = $request->file('contract_attachment');
            $contractPath = $file->store('contracts', 'public'); // Store in storage/app/public/contracts
        }

        // Create the training type and store the contract path if it exists
        TrainingType::create([
            'type_name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'contract' => $contractPath, // Store file path in database
        ]);

        return redirect()->back()->with('success', 'Training Type added successfully!');
    }


    public function updateTrainingType(Request $request, $id)
    {
        // Find the training type by ID
        $trainingType = TrainingType::findOrFail($id);

        // Validate the incoming request (including the contract attachment)
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:training_types,type_name,' . $id,
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:Active,Inactive',
            'contract_attachment' => 'nullable|mimes:pdf,doc,docx|max:10240', // Optional file validation
        ]);

        // Handle the file upload if there is a contract attachment
        $contractPath = $trainingType->contract; // Keep the current contract if no new file is uploaded
        if ($request->hasFile('contract_attachment')) {
            // If a new contract file is uploaded, store the new file and delete the old one if exists
            if ($contractPath) {
                // Delete the old contract file from storage
                Storage::disk('public')->delete($contractPath);
            }

            // Store the new file
            $file = $request->file('contract_attachment');
            $contractPath = $file->store('contracts', 'public'); // Store in storage/app/public/contracts
        }

        // Update the training type with the provided data
        $trainingType->update(array_merge(
            $request->only(['name', 'description', 'status']),
            ['contract' => $contractPath] // Update the contract path
        ));

        return redirect()->back()->with('success', 'Training Type updated successfully!');
    }



    public function destroyTrainingType($id)
    {
        $trainingType = TrainingType::findOrFail($id);
        $trainingType->delete();

        return redirect()->back()->with('success', 'Training Type deleted successfully!');
    }


    public function viewCertificate($id)
    {
        Log::info('View Certificate Request Received', ['training_id' => $id]);

        // Find the training record
        $training = Training::findOrFail($id);

        // Use the trainee_id to get the related achievement
        $achievement = TrainingAchievement::where('employee_id', $training->trainee_id)->first();

        if (!$achievement) {
            Log::warning('No achievement found for trainee', ['trainee_id' => $training->trainee_id]);
            return abort(404, 'No certificate found for this trainee.');
        }

        // Fetch employee data from API
        $token = env('HR1_API_KEY');
        $response = Http::withToken($token)->get("https://hr1.moverstaxi.com/api/v1/employees/{$achievement->employee_id}");

        if (!$response->successful()) {
            Log::error('Failed to fetch employee data from API', [
                'employee_id' => $achievement->employee_id,
                'status' => $response->status(),
            ]);
            return abort(404, 'Employee not found in API.');
        }

        $employee = $response->json();
        Log::info('Employee Data Fetched Successfully', ['employee' => $employee]);

        // Load the PDF with portrait mode
        $pdf = Pdf::loadView('trainings.certificate', [
            'achievement' => $achievement,
            'employee' => $employee,
            'training' => $training, // Pass training if needed in the view
        ])->setPaper('a4', 'portrait');

        Log::info('PDF Certificate Generated', ['achievement_id' => $achievement->id]);

        return $pdf->stream("certificate_{$achievement->id}.pdf");
    }
}
