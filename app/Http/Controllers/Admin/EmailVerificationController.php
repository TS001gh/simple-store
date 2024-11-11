<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailVerificationController extends Controller
{
    // Display the email verification notice
    public function showVerificationNotice()
    {
        return view('auth.verify');  // Ensure you have a verification notice view here
    }

    // Verify the email when the user clicks the link
    public function verify(EmailVerificationRequest $request)
    {
        dd("I'm here now");
        $request->fulfill();
        return redirect('/admin/dashboard');  // Redirect to the Backpack dashboard after verification
    }

    // Resend the verification email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/admin/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }
}
