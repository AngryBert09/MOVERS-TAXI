<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index()
    {
        $company = DB::table('company')->first(); // Fetch first company
        return view('settings', compact('company'));
    }

    public function contacts()
    {
        $company = DB::table('company')->first(); // Fetch first company
        return view('landing-pages.contact', compact('company'));
    }

    public function update(Request $request)
    {
        Log::debug('Entering update method.');
        Log::debug('Request data: ', $request->all());

        try {
            // Validate the request data
            $request->validate([
                'company_name' => 'required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:50',
                'mobile_number' => 'nullable|string|max:50',
                'fax' => 'nullable|string|max:50',
                'website_url' => 'nullable|string|max:255',
            ]);

            Log::debug('Validation passed.');

            // Check if there's any record in the company table
            $company = DB::table('company')->first();
            Log::debug('Fetched company: ', ['company' => $company]);

            if ($company) {
                Log::debug('Updating existing company...');

                // Build the update data dynamically
                $updateData = [];
                if ($request->has('company_name')) {
                    $updateData['company_name'] = $request->company_name;
                }
                if ($request->has('contact_person')) {
                    $updateData['contact_person'] = $request->contact_person;
                }
                if ($request->has('address')) {
                    $updateData['address'] = $request->address;
                }
                if ($request->has('email')) {
                    $updateData['email'] = $request->email;
                }
                if ($request->has('phone_number')) {
                    $updateData['phone_number'] = $request->phone_number;
                }
                if ($request->has('mobile_number')) {
                    $updateData['mobile_number'] = $request->mobile_number;
                }
                if ($request->has('fax')) {
                    $updateData['fax'] = $request->fax;
                }
                if ($request->has('website_url')) {
                    $updateData['website_url'] = $request->website_url;
                }

                // Always update the timestamp
                $updateData['updated_at'] = now();

                // Execute the update query
                $updated = DB::table('company')
                    ->where('id', $company->id)
                    ->update($updateData);

                if ($updated) {
                    Log::debug('Company updated successfully.');
                    return redirect()->back()->with('success', 'Company details updated successfully.');
                } else {
                    Log::error('Update query executed, but no rows were affected.');
                    return redirect()->back()->with('error', 'No changes were made. Please check the data and try again.');
                }
            } else {
                Log::debug('No existing company found, inserting new record...');
                DB::table('company')->insert([
                    'company_name' => $request->company_name,
                    'contact_person' => $request->contact_person,
                    'address' => $request->address,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'mobile_number' => $request->mobile_number,
                    'fax' => $request->fax,
                    'website_url' => $request->website_url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::debug('New company created successfully.');
                return redirect()->back()->with('success', 'Company details created successfully.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::error('Validation error: ', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Handle all other exceptions
            Log::error('Error updating company: ', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while updating the company details. Please try again.');
        }
    }
}
