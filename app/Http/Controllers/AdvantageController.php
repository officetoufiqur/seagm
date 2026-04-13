<?php

namespace App\Http\Controllers;

use App\Models\Advantage;
use App\Models\AdvantageCard;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvantageController extends Controller
{
    public function index()
    {
        $advantages = Advantage::all();

        $card = AdvantageCard::first();

        return Inertia::render('Advantage/Index', [
            'advantages' => $advantages,
            'card' => $card
        ]);
    }

    public function create()
    {
        return Inertia::render('Advantage/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string',
        ]);

        $page = Page::where('slug', 'advantages')->first();

        $advantage = new Advantage();
        $advantage->page_id = $page->id;
        $advantage->label = $request->label;
        $advantage->value = $request->value;
        $advantage->save();

        return redirect()->route('advantage.index')->with('message', 'Advantage created successfully.');
    }

    public function edit($id)
    {
        $advantage = Advantage::findOrFail($id);

        return Inertia::render('Advantage/Edit', [
            'advantage' => $advantage
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string',
        ]);

        $advantage = Advantage::findOrFail($id);
        $advantage->label = $request->label;
        $advantage->value = $request->value;
        $advantage->save();

        return redirect()->route('advantage.index')->with('message', 'Advantage updated successfully.');
    }

    public function destroy($id)
    {
        $advantage = Advantage::findOrFail($id);
        $advantage->delete();

        return redirect()->route('advantage.index')->with('message', 'Advantage deleted successfully.');
    }

    public function cardEdit()
    {
        $card = AdvantageCard::first();

        return Inertia::render('Advantage/Card', [
            'card' => $card
        ]);
    }

    public function cardUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $card = AdvantageCard::first();

        $card->title = $request->title;
        $card->description = $request->description;
        $card->save();

        return redirect()->route('advantage.index')->with('message', 'Card updated successfully.');
    }
}
