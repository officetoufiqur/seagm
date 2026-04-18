<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Star;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StarController extends Controller
{
    public function index()
    {
        $star = Star::first();

        return Inertia::render('Star/Index', ['star' => $star]);
    }

    public function edit()
    {
        $star = Star::with('items')->first();

        return Inertia::render('Star/Edit', ['star' => $star]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'heading' => 'required|string',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'items' => 'required|array',
            'items.*.title' => 'required|string|max:255',
            'items.*.subtitle' => 'required|string',
            'items.*.image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $star = Star::first();

        $file = $star->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/stars', $file);
        }

        $star->update([
            'heading' => $request->heading,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $file,
        ]);

        $existingItems = $star->items()->get();
        $existingIds = $existingItems->pluck('id')->toArray();

        $requestIds = [];

        foreach ($request->items ?? [] as $item) {
            $itemImage = null;

            if (isset($item['id'])) {
                $oldItem = $existingItems->where('id', $item['id'])->first();
                $itemImage = $oldItem?->image;
            }

            if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                $itemImage = FileUpload::updateFile(
                    $item['image'],
                    'uploads/stars/items',
                    $itemImage
                );
            }

            if (! empty($item['id'])) {

                $requestIds[] = $item['id'];

                $star->items()->where('id', $item['id'])->update([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'image' => $itemImage,
                ]);

            } else {

                $newItem = $star->items()->create([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'image' => $itemImage,
                ]);

                $requestIds[] = $newItem->id;
            }
        }

        $deleteIds = array_diff($existingIds, $requestIds);

        if (! empty($deleteIds)) {
            $star->items()->whereIn('id', $deleteIds)->delete();
        }

        return redirect()->route('star.index')->with('message', 'Star updated successfully.');
    }
}
