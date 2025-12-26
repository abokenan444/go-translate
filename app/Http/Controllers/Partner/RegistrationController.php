<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('partners.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|unique:partners,email',
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'website' => 'nullable|url',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $partner = Partner::create($request->all());

        return redirect()->route('partners.success')
            ->with('success', 'Your partner application has been submitted successfully!');
    }

    public function success()
    {
        return view('partners.success');
    }
}
