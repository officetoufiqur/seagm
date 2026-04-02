<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\CardItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CardItemController extends Controller
{
    public function index()
    {
        $cards = CardItem::all();

        return Inertia::render('Card/Index', [
            'cards' => $cards,
        ]);
    }

    public function edit(CardItem $card)
    {
        return Inertia::render('Card/Edit', [
            '$card' => $card,
        ]);
    }

    public function update(Request $request, $id)
    {
        $card = CardItem::findOrFail($id);

        $file = null;

        if ($request->hasFile('image')) {
           if ($card->image) {
                FileUpload::deleteFile($card->image);
            }

            $file = FileUpload::storeFile($request->file('image'), 'uploads/cards');
        }

        $card->update([
            'category_name' => $request->category_name,
            'par_value_currency' => $request->par_value_currency,
            'par_value' => $request->par_value,
            'currency' => $request->currency,
            'unit_price' => $request->unit_price,
            'max_amount' => $request->max_amount,
            'min_amount' => $request->min_amount,
            'origin_price' => $request->origin_price,
            'discount_rate' => $request->discount_rate,
            'has_stock' => $request->has_stock ?? true
        ]);

        return redirect()->route('cards.index')->with('message', 'Card updated successfully.');
    }

    public function destroy($id)
    {
        $card = CardItem::findOrFail($id);

        if ($card->image) {
            FileUpload::deleteFile($card->image);
        }

        $card->delete();

        return redirect()->route('cards.index')->with('message', 'Card deleted successfully.');
    }
}
