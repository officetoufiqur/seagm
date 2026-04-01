<?php

namespace App\Http\Controllers;

use App\Helpers\SeagmHelper;
use App\Models\CardCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CardCategoryController extends Controller
{
    public function index()
    {
        $response = SeagmHelper::get('v1/card-categories');

        collect($response['data'])->each(function ($category) {
            $categoryData = CardCategory::updateOrCreate(
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
                Product::updateOrCreate(
                    ['api_id' => $card['id']],
                    [
                        'category_id' => $categoryData->id,
                        'api_category' => $card['category_id'],
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

        $categories = CardCategory::all();

        return Inertia::render('CardCategory/Index', [
            'categories' => $categories,
        ]);
    }

    public function edit(CardCategory $category)
    {
        return Inertia::render('CardCategory/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = CardCategory::findOrFail($id);

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
        $category = CardCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('card-categories.index')->with('message', 'Card category deleted successfully.');
    }
}
