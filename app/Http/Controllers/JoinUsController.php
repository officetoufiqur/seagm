<?php

namespace App\Http\Controllers;

use App\Models\JoinUs;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JoinUsController extends Controller
{
    public function index()
    {
        $joinus = JoinUs::get();

        return Inertia::render('JoinUs/Index', compact('joinus'));
    }

    public function create()
    {
        return Inertia::render('JoinUs/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
        ]);

        $joinus = JoinUs::create([
            'title' => $request->title,
            'icon' => $request->icon,
        ]);

        foreach ($request->items as $item) {
            $joinus->items()->create([
                'title' => $item['title'],
            ]);
        }

        return redirect()->route('join-us.index')->with('message', 'JoinUs created successfully.');

    }

    public function edit($id)
    {
        $joinus = JoinUs::with('items')->findOrFail($id);

        return Inertia::render('JoinUs/Edit', compact('joinus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
        ]);

        $joinus = JoinUs::findOrFail($id);

        $joinus->update([
            'title' => $request->title,
            'icon' => $request->icon,
        ]);

        $existingIds = [];

        foreach ($request->items as $item) {

            if (isset($item['id'])) {
                $existingItem = $joinus->items()->find($item['id']);
                if ($existingItem) {
                    $existingItem->update([
                        'title' => $item['title'],
                    ]);
                    $existingIds[] = $existingItem->id;
                }
            } else {
                $newItem = $joinus->items()->create([
                    'title' => $item['title'],
                ]);
                $existingIds[] = $newItem->id;
            }
        }

        $joinus->items()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('join-us.index')
            ->with('message', 'JoinUs updated successfully.');
    }

    public function destroy($id)
    {
        $joinus = JoinUs::findOrFail($id);
        $joinus->delete();

        return redirect()->route('join-us.index')
            ->with('message', 'JoinUs deleted successfully.');
    }
}
