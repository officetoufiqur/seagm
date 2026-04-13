<?php

namespace App\Http\Controllers;

use App\Models\ThroughUsItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThroughController extends Controller
{
    public function index()
    {
        $throughs = ThroughUsItem::all();

        return Inertia::render('Through/Index', compact('throughs'));
    }

    public function create()
    {
        return Inertia::render('Through/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'icon' => 'required'
        ]);

        ThroughUsItem::create([
            'title' => $request->title,
            'icon' => $request->icon
        ]);

        return redirect()->route('through.index')->with('message', 'Through created successfully.');
    }

    public function edit($id)
    {
        $through = ThroughUsItem::find($id);

        return Inertia::render('Through/Edit', compact('through'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'icon' => 'required'
        ]);

        $through = ThroughUsItem::find($id);

        $through->update([
            'title' => $request->title,
            'icon' => $request->icon
        ]);

        return redirect()->route('through.index')->with('message', 'Through updated successfully.');
    }

    public function destroy($id)
    {
        ThroughUsItem::find($id)->delete();

        return redirect()->route('through.index')->with('message', 'Through deleted successfully.');
    }
}
