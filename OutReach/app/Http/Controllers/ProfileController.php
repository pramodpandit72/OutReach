<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * ProfileController
 *
 * Handles showing the profile edit form and processing profile updates
 * for both customers and business users. Supports password changes.
 */
class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('auth.profile-edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ];

        // Add business-specific rules if the user is a business
        if ($user->isBusiness()) {
            $rules['company_name'] = 'nullable|string|max:255';
            $rules['company_description'] = 'nullable|string|max:1000';
        }

        // Add password change validation rules if password fields are provided
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if email is unique manually to prevent MongoDB compatibility issues with standard Eloquent validator
        $existingUser = User::where('email', $request->email)
            ->where('_id', '!=', (string) $user->_id)
            ->first();

        if ($existingUser) {
            return redirect()->back()
                ->withErrors(['email' => 'The email has already been taken.'])
                ->withInput();
        }

        // Verify current password if changing password
        if ($request->filled('password')) {
            // Note: Google-only accounts might not have a password set initially.
            // If they don't have a password set, we bypass current password check to let them set a password.
            $hasPassword = !empty($user->getAttributes()['password'] ?? null);

            if ($hasPassword && !Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'The current password you entered is incorrect.'])
                    ->withInput();
            }

            $user->password = $request->password;
        }

        // Update fields
        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->isBusiness()) {
            $user->company_name = $request->company_name;
            $user->company_description = $request->company_description;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
