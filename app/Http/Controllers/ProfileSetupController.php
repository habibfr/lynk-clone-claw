<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileSetupController extends Controller
{
    public function show()
    {
        // If profile exists, redirect to dashboard
        if (Auth::user()->profile) {
            return redirect()->route('dashboard');
        }

        return view('profile.setup');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'alpha_dash',
                Rule::unique('profiles', 'username'),
            ],
            'display_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        $profile = Auth::user()->profile()->create([
            'username' => strtolower($validated['username']),
            'display_name' => $validated['display_name'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile created successfully!');
    }
}
