<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function index()
    {
        $links = Auth::user()->profile->links()->orderBy('order')->get();
        return view('links.index', compact('links'));
    }

    public function create()
    {
        return view('links.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:255',
        ]);

        $profile = Auth::user()->profile;
        
        $link = $profile->links()->create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? null,
            'order' => $profile->links()->max('order') + 1,
        ]);

        return redirect()->route('links.index')->with('success', 'Link created successfully!');
    }

    public function edit(Link $link)
    {
        $this->authorize('update', $link);
        return view('links.edit', compact('link'));
    }

    public function update(Request $request, Link $link)
    {
        $this->authorize('update', $link);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $link->update($validated);

        return redirect()->route('links.index')->with('success', 'Link updated successfully!');
    }

    public function destroy(Link $link)
    {
        $this->authorize('delete', $link);
        $link->delete();

        return redirect()->route('links.index')->with('success', 'Link deleted successfully!');
    }

    public function redirect($id)
    {
        $link = Link::findOrFail($id);
        
        // Track click
        Click::create([
            'link_id' => $link->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'clicked_at' => now(),
        ]);

        // Increment counter
        $link->increment('clicks');

        return redirect($link->url);
    }

    public function analytics()
    {
        $profile = Auth::user()->profile;
        $links = $profile->links()->withCount('clickRecords')->get();
        $totalClicks = $profile->links()->sum('clicks');
        
        // Recent clicks (last 30 days)
        $recentClicks = Click::whereHas('link', function($query) use ($profile) {
            $query->where('profile_id', $profile->id);
        })->where('clicked_at', '>=', now()->subDays(30))
          ->orderBy('clicked_at', 'desc')
          ->take(100)
          ->get();

        return view('analytics', compact('links', 'totalClicks', 'recentClicks'));
    }
}
