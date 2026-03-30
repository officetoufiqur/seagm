<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\News;
use App\Models\Promotion;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromotionController extends Controller
{
    use ApiResponse;

    public function home()
    {
        $promotions = Promotion::with('items')->where('status', 1)->get();

        if ($promotions->isEmpty()) {
            return $this->errorResponse('Promotions not found.', 404);
        }

         $coupons = Coupon::with('product:id,name,code,image')->where('is_active', true)->get();

        if ($coupons->isEmpty()) {
            return $this->errorResponse('Coupons not found.', 404);
        }

        $banners = Banner::where('status', 1)->get();

        if ($banners->isEmpty()) {
            return $this->errorResponse('Banners not found.', 404);
        }

        $news = News::with('category:id,name', 'author:id,name')->where('status', 1)->get();

        if ($news->isEmpty()) {
            return $this->errorResponse('News not found.', 404);
        }

        $data = [
            'banners' => $banners,
            'promotions' => $promotions,
            'news' => $news,
            'coupons' => $coupons
        ];

        return $this->successResponse($data, 'Home module fetched successfully.');
    }

    public function promotionDetails($id)
    {
        $promotion = Promotion::with('items')->find($id);

        if (!$promotion) {
            return $this->errorResponse('Promotion not found.', 404);
        }

        return $this->successResponse($promotion, 'Promotion fetched successfully.');
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
            'description' => 'required|string',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.country' => 'required|string|max:255',
            'items.*.sales_count' => 'required|integer|max:255',
            'items.*.rating' => 'required|numeric|between:0,5',
            'items.*.card_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/promotions');
        }

        $icon = null;
        if ($request->hasFile('icon')) {
            $icon = FileUpload::storeFile($request->file('icon'), 'uploads/promotions');
        }

        $promotion = new Promotion;

        $promotion->heading = $request->heading;
        $promotion->title = $request->title;
        $promotion->subtitle = $request->subtitle;
        $promotion->icon = $icon;
        $promotion->description = $request->description;
        $promotion->image = $file;
        $promotion->save();

        foreach ($request->items as $item) {
            $cardFile = null;

            if (isset($item['card_image'])) {
                $cardFile = FileUpload::storeFile($item['card_image'], 'uploads/promotions');
            }

            $promotion->items()->create([
                'title' => $item['title'],
                'country' => $item['country'],
                'sales_count' => $item['sales_count'],
                'rating' => $item['rating'],
                'card_image' => $cardFile,
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
            'description' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.country' => 'required|string|max:255',
            'items.*.sales_count' => 'required|integer|max:255',
            'items.*.rating' => 'required|numeric|between:0,5',
            'items.*.card_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = $promotion->image;
        $icon = $promotion->icon;

        if ($request->hasFile('icon')) {
            FileUpload::deleteFile($promotion->icon);
            $icon = FileUpload::storeFile($request->file('icon'), 'uploads/promotions');
        }
        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/promotions', $file);
        }

        $promotion->heading = $request->heading;
        $promotion->title = $request->title;
        $promotion->subtitle = $request->subtitle;
        $promotion->icon = $icon;
        $promotion->description = $request->description;
        $promotion->image = $file;
        $promotion->save();

        $existingIds = [];

        foreach ($request->items as $index => $item) {

            // UPDATE
            if (! empty($item['id'])) {

                $promotionItem = $promotion->items()->find($item['id']);

                if ($promotionItem) {

                    $itemImage = $promotionItem->card_image;

                    if ($request->hasFile("items.$index.card_image")) {
                        FileUpload::deleteFile($promotionItem->card_image);

                        $itemImage = FileUpload::storeFile($request->file("items.$index.card_image"), 'uploads/promotions');
                    }

                    $promotionItem->update([
                        'title' => $item['title'],
                        'country' => $item['country'],
                        'sales_count' => $item['sales_count'],
                        'rating' => $item['rating'],
                        'card_image' => $itemImage,
                    ]);

                    $existingIds[] = $promotionItem->id;
                }

            } else {
                $cardFile = null;

                if ($request->hasFile("items.$index.card_image")) {
                    $cardFile = FileUpload::storeFile(
                        $request->file("items.$index.card_image"),
                        'uploads/promotions'
                    );
                }

                $newItem = $promotion->items()->create([
                    'title' => $item['title'],
                    'country' => $item['country'],
                    'sales_count' => $item['sales_count'],
                    'rating' => $item['rating'],
                    'card_image' => $cardFile,
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
        FileUpload::deleteFile($promotion->icon);
        FileUpload::deleteFile($promotion->image);
        $promotion->delete();

        return redirect()->route('promotions.index')->with('message', 'Promotion deleted successfully.');
    }
}
