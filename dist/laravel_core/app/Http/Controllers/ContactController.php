<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use App\Models\Unit;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $unit = null;
        if ($request->has('unit_id')) {
            $unit = Unit::with('property')->find($request->unit_id);
        }
        return view('public.contact', compact('unit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10',
            'unit_id' => 'nullable|exists:units,id',
            'g-recaptcha-response' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Verify with Google
                    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => config('services.recaptcha.secret_key'),
                        'response' => $value,
                        'remoteip' => request()->ip(),
                    ]);

                    if (!$response->json('success')) {
                        $fail('The reCAPTCHA verification failed. Please try again.');
                    }
                }
            ],
        ]);

        $unit = null;
        if (!empty($validated['unit_id'])) {
            $unit = Unit::with('property')->find($validated['unit_id']);
        }

        // Find Admin User for Notification
        // Prefer an admin with 'contact_notification_email' set, otherwise fallback to first admin or hardcoded default
        $admin = User::where('role', 'admin')
            ->whereNotNull('contact_notification_email')
            ->first();

        $recipient = $admin ? $admin->contact_notification_email : (User::where('role', 'admin')->first()->email ?? 'info@riyana-immobilien.de');

        // Send Email
        Mail::to($recipient)->send(new ContactFormSubmitted($validated, $unit));

        return back()->with('success', 'Vielen Dank für Ihre Nachricht! Wir werden uns so schnell wie möglich bei Ihnen melden.');
    }
}
