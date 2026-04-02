<?php

namespace App\Http\Controllers;

use App\Helpers\SeagmHelper;
use App\Models\Card;
use App\Models\CardItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CardController extends Controller
{
    public function index()
    {
        $response = SeagmHelper::get('v1/card-categories');

        collect($response['data'])->each(function ($category) {
            $categoryData = Card::updateOrCreate(
                ['api_id' => $category['id']],
                [
                    'name' => $category['name'] ?? null,
                    'code' => $category['code'] ?? null,
                    'mode' => $category['mode'] ?? null,
                    'region' => $category['region'] ?? null,
                    'publisher' => $category['publisher'] ?? null,
                    'auto_delivery' => $category['auto_delivery'] ?? false,
                ]
            );

            $id = $categoryData->api_id;

            $cards = SeagmHelper::get('v1/card-categories/'.$id.'/card-types');

            collect($cards['data'])->each(function ($card) use ($categoryData) {
                CardItem::updateOrCreate(
                    ['api_id' => $card['id']],
                    [
                        'card_id' => $categoryData->id,
                        'name' => $card['name'],
                        'api_category_id' => $card['category_id'],
                        'category_name' => $card['category_name'],
                        'par_value_currency' => $card['par_value_currency'],
                        'par_value' => $card['par_value'],
                        'currency' => $card['currency'],
                        'unit_price' => $card['unit_price'],
                        'max_amount' => $card['max_amount'],
                        'min_amount' => $card['min_amount'],
                        'origin_price' => $card['origin_price'],
                        'discount_rate' => $card['discount_rate'],
                        'has_stock' => $card['has_stock'],
                    ]
                );
            });

        });

        $categories = Card::all();

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
