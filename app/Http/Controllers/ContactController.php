<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Contact;
use App\Models\ContactNumber;
use App\Models\Social;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ContactController extends Controller
{
    use ApiResponse;

    public function contact()
    {
        $contact = Contact::first();
        $number = ContactNumber::all();
        $social = Social::all();

        $thailandFacebook = $social->first(function ($item) {
            return str_contains(strtolower($item->url), 'seagmthailand');
        });

        $filteredSocial = $social->reject(function ($item) {
            return str_contains(strtolower($item->url), 'seagmthailand');
        })->values();

        $data = [
            'contact' => $contact,
            'number' => $number,
            'social' => $filteredSocial,
            'thailand_facebook' => $thailandFacebook,
        ];

        return $this->successResponse($data, 'Contact retrieved successfully');
    }

    public function index()
    {
        $contacts = Contact::first();

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function edit()
    {
        $contact = Contact::first();
        $number = ContactNumber::all();

        return Inertia::render('Contacts/Edit', [
            'contacts' => $contact,
            'number' => $number,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'address_title' => 'required|string|max:255',
            'map' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $contact = Contact::first();

        $file = $contact->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/contacts', $file);
        }

        $contact->update([
            'heading' => $request->heading,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'address_title' => $request->address_title,
            'map' => $request->map,
            'image' => $file,
        ]);

        return redirect()->route('contacts.edit')->with('message', 'Contact updated successfully.');
    }

    public function updateNumbers(Request $request)
    {
        $request->validate([
            'numbers' => 'required|array',
            'numbers.*.id' => 'required|exists:contact_numbers,id',
            'numbers.*.title' => 'required|string|max:255',
            'numbers.*.subtitle' => 'nullable|string',
            'numbers.*.numbers' => 'required|array',
            'numbers.*.numbers.*' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->numbers as $item) {

                $contact = ContactNumber::findOrFail($item['id']);

                $contact->update([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'numbers' => $item['numbers'],
                ]);
            }

            DB::commit();

            return redirect()->route('contacts.edit')->with('message', 'Contact numbers updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
