<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        $profile = Profile::where('username', $username)
            ->where('is_active', true)
            ->with(['links' => function($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->firstOrFail();

        return view('public.profile', compact('profile'));
    }
}
