<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class CardController extends Controller
{
    public function index()
    {
        $categories = Cache::remember('card_categories_db', 300, function () {
            return Card::get();
        });

        return Inertia::render('CardCategory/Index', [
            'categories' => $categories,
        ]);
    }

    public function edit(Card $category)
    {
        return Inertia::render('CardCategory/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Card::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'code' => $request->code,
            'mode' => $request->mode,
            'region' => $request->region,
            'publisher' => $request->publisher,
            'auto_delivery' => $request->auto_delivery,
            'icon' => $request->icon,
            'status' => 1,
        ]);

        return redirect()->route('card-categories.index')->with('message', 'Card category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Card::findOrFail($id);
        $category->delete();

        return redirect()->route('card-categories.index')->with('message', 'Card category deleted successfully.');
    }
}
