<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Submit contact form
     */
    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
            ]);

            // Log the contact form submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

            // TODO: Send email notification to admin
            // Mail::to(config('mail.admin_email'))->send(new ContactFormSubmitted($validated));

            return redirect()->back()->with('success', __('شكراً لتواصلك معنا! سنرد عليك في أقرب وقت ممكن.'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', __('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.'))
                ->withInput();
        }
    }
}
