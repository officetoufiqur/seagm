<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Promotion;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromotionController extends Controller
{
    use ApiResponse;

    public function promotions()
    {
        $promotions = Promotion::with('items')->where('status', 1)->get();

        if ($promotions->isEmpty()) {
            return $this->errorResponse('Promotions not found.', 404);
        }

        return $this->successResponse($promotions, 'Promotions fetched successfully.');
    }

    public function index()
    {
        $promotions = Promotion::with('items')->get();

        return Inertia::render('Promotion/Index', [
            'promotions' => $promotions,
        ]);
    }

    public function create()
    {
        return Inertia::render('Promotion/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'subtitle' => 'required|string|max:500',
            'icon' => 'required|string|max:500',
            'description' => 'required|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.country' => 'required|string|max:255',
            'items.*.sales_count' => 'required|integer|max:255',
            'items.*.rating' => 'required|numeric|max:255',
            'items.*.image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/promotions');
        }

        $promotion = new Promotion;

        $promotion->heading = $request->heading;
        $promotion->title = $request->title;
        $promotion->subtitle = $request->subtitle;
        $promotion->icon = $request->icon;
        $promotion->description = $request->description;
        $promotion->image = $file;
        $promotion->save();

        foreach ($request->items as $item) {
            $cardFile = null;

            if ($request->hasFile('image')) {
                $cardFile = FileUpload::storeFile($item['image'], 'uploads/promotions');
            }

            $promotion->items()->create([
                'title' => $item['title'],
                'country' => $item['country'],
                'sales_count' => $item['sales_count'],
                'rating' => $item['rating'],
                'image' => $cardFile,
            ]);
        }

        return redirect()->route('promotions.index')->with('message', 'Promotion created successfully.');
    }

    public function edit($id)
    {
        $promotion = Promotion::with('items')->findOrFail($id);

        return Inertia::render('Promotion/Edit', [
            'promotion' => $promotion,
        ]);
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'subtitle' => 'required|string|max:500',
            'icon' => 'required|string|max:500',
            'description' => 'required|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.country' => 'required|string|max:255',
            'items.*.sales_count' => 'required|integer|max:255',
            'items.*.rating' => 'required|numeric|max:255',
            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = $promotion->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/promotions', $file);
        }

        $promotion->heading = $request->heading;
        $promotion->title = $request->title;
        $promotion->subtitle = $request->subtitle;
        $promotion->icon = $request->icon;
        $promotion->description = $request->description;
        $promotion->image = $file;
        $promotion->save();

        $existingIds = [];

        foreach ($request->items as $index => $item) {

            // UPDATE
            if (! empty($item['id'])) {

                $promotionItem = $promotion->items()->find($item['id']);

                if ($promotionItem) {

                    $itemImage = $promotionItem->image;

                    if ($request->hasFile("items.$index.image")) {
                        FileUpload::deleteFile($promotionItem->image);

                        $itemImage = FileUpload::storeFile($request->file("items.$index.image"), 'uploads/promotions');
                    }

                    $promotionItem->update([
                        'title' => $item['title'],
                        'country' => $item['country'],
                        'sales_count' => $item['sales_count'],
                        'rating' => $item['rating'],
                        'image' => $itemImage,
                    ]);

                    $existingIds[] = $promotionItem->id;
                }

            } else {
                $cardFile = null;

                if ($request->hasFile("items.$index.image")) {
                    $cardFile = FileUpload::storeFile($item['image'], 'uploads/promotions');
                }

                $newItem = $promotion->items()->create([
                    'title' => $item['title'],
                    'country' => $item['country'],
                    'sales_count' => $item['sales_count'],
                    'rating' => $item['rating'],
                    'image' => $cardFile,
                ]);

                $existingIds[] = $newItem->id;
            }
        }

        $promotion->items()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('promotions.index')->with('message', 'Promotion updated successfully.');
    }

    public function status(Promotion $promotion)
    {
        $promotion->status = ! $promotion->status;
        $promotion->save();

        return redirect()->route('promotions.index')->with('message', 'Promotion status updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        FileUpload::deleteFile($promotion->image);
        $promotion->delete();

        return redirect()->route('promotions.index')->with('message', 'Promotion deleted successfully.');
    }
}
