<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\AboutContact;
use App\Models\Social;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutContactController extends Controller
{
    use ApiResponse;

    public function show()
    {
        $contact = AboutContact::first();

        $socials = Social::where('section', 'about_social')->get();

        return $this->successResponse([
            'contact' => $contact,
            'socials' => $socials
        ], 'About Contact');
    }
    
    public function index()
    {
        $contact = AboutContact::first();

        return Inertia::render('AboutContact/Index', [
            'contact' => $contact
        ]);
    }

    public function edit($id)
    {
        $contact = AboutContact::findOrFail($id);

        return Inertia::render('AboutContact/Edit', [
            'contact' => $contact
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $contact = AboutContact::findOrFail($id);

        $file = $contact->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/about_contacts', $file);
        }

        $contact->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $file
        ]);

        return redirect()->route('about-contact.index')->with('message', 'About Contact updated successfully.');
    }
}
