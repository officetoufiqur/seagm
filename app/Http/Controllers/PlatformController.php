<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Platform;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PlatformController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        $platforms = Platform::all();

        return Inertia::render('Platform/Index', [
            'platforms' => $platforms,
        ]);
    }

    public function edit($id)
    {
        $platform = Platform::with('items', 'images')->findOrFail($id);
        
        return Inertia::render('Platform/Edit', [
            'platform' => $platform,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section' => 'required',
            'title' => 'required',
            'items' => 'nullable|array',
            'items.*.title' => 'required|string|max:255',
            'items.*.icon' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::transaction(function () use ($request, $id) {

            $platform = Platform::findOrFail($id);

            $platform->update([
                'section' => $request->section,
                'title' => $request->title,
            ]);

            /*
            |----------------------------------
            | ITEMS
            |----------------------------------
            */
            $requestIds = [];

            foreach ($request->items ?? [] as $item) {

                if (! empty($item['id'])) {

                    $platform->items()->where('id', $item['id'])->update([
                        'title' => $item['title'],
                        'icon' => $item['icon'],
                    ]);

                    $requestIds[] = $item['id'];

                } else {

                    $newItem = $platform->items()->create([
                        'title' => $item['title'],
                        'icon' => $item['icon'],
                    ]);

                    $requestIds[] = $newItem->id;
                }
            }

       
            if (count($requestIds)) {
                $platform->items()->whereNotIn('id', $requestIds)->delete();
            } else {
                $platform->items()->delete();
            }

            /*
            |----------------------------------
            | IMAGES
            |----------------------------------
            */
            $existingImages = $platform->images()->get();
            $requestImageIds = [];

            foreach ($request->images ?? [] as $image) {

                $imageFile = null;

                if (! empty($image['id'])) {
                    $oldImage = $existingImages->where('id', $image['id'])->first();
                    $imageFile = $oldImage?->image;
                }

                if (isset($image['image'])) {
                    $imageFile = FileUpload::updateFile(
                        $image['image'],
                        'uploads/platforms',
                        $imageFile
                    );
                }

                if (! empty($image['id'])) {

                    $platform->images()->where('id', $image['id'])->update([
                        'image' => $imageFile,
                    ]);

                    $requestImageIds[] = $image['id'];

                } else {

                    $newImage = $platform->images()->create([
                        'image' => $imageFile,
                    ]);

                    $requestImageIds[] = $newImage->id;
                }
            }

            // delete removed images safely
            if (count($requestImageIds)) {
                $platform->images()->whereNotIn('id', $requestImageIds)->delete();
            } else {
                $platform->images()->delete();
            }

        });

        return redirect()->route('platform.index')->with('message', 'Platform updated successfully');
    }

    public function show()
    {
        $platform = Platform::with('items', 'images')->where('section', 'platform')->first();
        $kaleoz = Platform::with('items', 'images')->where('section', 'kaleoz')->first();
        $news = Platform::with('items', 'images')->where('section', 'news')->first();

        $data = [
            'platform' => $platform,
            'kaleoz' => $kaleoz,
            'news' => $news,
        ];
        
        return $this->successResponse($data, 'Platform information retrieved successfully.');
    }
}
