<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::all();

        return Inertia::render('Carousel/Index', [
            'carousels' => $carousels
        ]);
    }

    public function create()
    {
        return Inertia::render('Carousel/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/carousel');
        }

        Carousel::create([
            'image' => $file
        ]);

        return redirect()->route('carousels.index')->with('message', 'Carousel created successfully.');
    }

    public function edit($id)
    {
        $carousel = Carousel::find($id);

        return Inertia::render('Carousel/Edit', [
            'carousel' => $carousel
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $carousel = Carousel::find($id);

        $file = $carousel->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/carousel', $file);
        }

        $carousel->image = $file;
        $carousel->save();

        return redirect()->route('carousels.index')->with('message', 'Carousel updated successfully.');
    }

    public function destroy($id)
    {
        $carousel = Carousel::find($id);
        if ($carousel->image) {
            FileUpload::deleteFile($carousel->image);
        }
        $carousel->delete();

        return redirect()->route('carousels.index')->with('message', 'Carousel deleted successfully.');
    }
}
