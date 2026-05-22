<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * ForgotPasswordController
 *
 * Implements a secure, MongoDB-compatible custom password reset flow.
 * Stores tokens directly in the user document dynamically.
 * Deliveries reset links via system logs, with a local screen-flash helper for testing.
 */
class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password email request form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate secure reset token and log/send the reset email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'We could not find a user with that email address.'])
                ->withInput();
        }

        // Generate secure 60-char token and 1-hour expiry
        $token = Str::random(60);
        $user->password_reset_token = $token;
        $user->password_reset_expires_at = now()->addHour();
        $user->save();

        // Build the reset URL
        $resetLink = route('password.reset', [
            'token' => $token,
            'email' => $user->email
        ]);

        // Log the link (standard Laravel MAIL_MAILER=log destination)
        Log::info("Password reset link generated for user: {$user->email}. Link: {$resetLink}");

        // Local development helper: Flash to screen so user can copy-paste and test instantly!
        if (config('app.env') === 'local') {
            return redirect()->back()
                ->with('status', 'A reset link has been generated and logged in laravel.log.')
                ->with('debug_link', $resetLink);
        }

        return redirect()->back()
            ->with('status', 'We have sent your password reset link! (Logged in storage/logs/laravel.log)');
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Perform password reset update.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || ($user->password_reset_token !== $request->token)) {
            return redirect()->back()
                ->withErrors(['email' => 'Invalid email or password reset token.'])
                ->withInput();
        }

        // Check if token has expired
        if ($user->password_reset_expires_at && now()->greaterThan($user->password_reset_expires_at)) {
            return redirect()->back()
                ->withErrors(['email' => 'This password reset link has expired. Please request a new one.'])
                ->withInput();
        }

        // Hash and update the password
        $user->password = $request->password;
        
        // Clear reset token and expiry
        $user->password_reset_token = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Your password has been successfully reset! Please sign in with your new password.');
    }
}
