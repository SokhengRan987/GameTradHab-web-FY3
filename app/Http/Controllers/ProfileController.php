<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'               => 'required|string|max:255',
            'full_name'          => 'nullable|string|max:255',
            'username'           => 'required|string|max:255|unique:users,username,' . $user->id,
            'country'            => 'nullable|string|max:10',
            'date_of_birth'      => 'nullable|date|before:today',
            'phone_country_code' => 'nullable|string|max:10',
            'phone_number'       => 'nullable|string|max:20',
            'telegram'           => 'nullable|string|max:100',
            'whatsapp'           => 'nullable|string|max:20',
            'discord'            => 'nullable|string|max:100',
            'line_id'            => 'nullable|string|max:100',
        ]);

        // Check if profile is completed
        $profileCompleted = $request->full_name
            && $request->country
            && $request->phone_number;

        $user->update([
            'name'               => $request->name,
            'full_name'          => $request->full_name,
            'username'           => $request->username,
            'country'            => $request->country,
            'date_of_birth'      => $request->date_of_birth,
            'phone_country_code' => $request->phone_country_code,
            'phone_number'       => $request->phone_number,
            'telegram'           => $request->telegram,
            'whatsapp'           => $request->whatsapp,
            'discord'            => $request->discord,
            'line_id'            => $request->line_id,
            'profile_completed'  => $profileCompleted,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        auth()->logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
