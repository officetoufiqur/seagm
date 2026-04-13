<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SocialController extends Controller
{
    public function index()
    {
        $socials = Social::all();

        return Inertia::render('Social/Index', [
            'socials' => $socials
        ]);
    }

    public function create()
    {
        return Inertia::render('Social/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'required|string',
        ]);

        Social::create([
            'section' => 'about_social',
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $request->icon,
        ]);

        return redirect()->route('socials.index')->with('message', 'Social created successfully.');
    }

    public function edit($id)
    {
        $social = Social::findOrFail($id);

        return Inertia::render('Social/Edit', [
            'social' => $social
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'required|string',
        ]);

        $social = Social::findOrFail($id);

        $social->update([
            'title' => $request->title,
            'url' => $request->url,
            'icon' => $request->icon,
        ]);

        return redirect()->route('socials.index')->with('message', 'Social updated successfully.');
    }

    public function destroy($id)
    {
        $social = Social::findOrFail($id);
        $social->delete();

        return redirect()->route('socials.index')->with('message', 'Social deleted successfully.');
    }
}
