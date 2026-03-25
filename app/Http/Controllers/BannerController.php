<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Banner;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BannerController extends Controller
{
    use ApiResponse;

    public function banners()
    {
        $banners = Banner::where('status', 1)->get();

        if ($banners->isEmpty()) {
            return $this->errorResponse('Banners not found.', 404);
        }

        return $this->successResponse($banners, 'Banners fetched successfully.');
    }
    
    public function index()
    {
        $banners = Banner::all();
        
        return Inertia::render('Banners/Index', [
            'banners' => $banners
        ]);
    }

    public function create()
    {
        return Inertia::render('Banners/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/banners');
        }

        $banner = new Banner();
        $banner->url = $request->url;
        $banner->image = $file;
        $banner->save();

        return redirect()->route('banners.index')->with('message', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return Inertia::render('Banners/Edit', [
            'banner' => $banner
        ]);
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'url' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = $banner->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/banners', $file);
        }

        $banner->url = $request->url;
        $banner->image = $file;
        $banner->save();

        return redirect()->route('banners.index')->with('message', 'Banner updated successfully.');
    }

    public function status(Banner $banner)
    {
        $banner->status = !$banner->status;
        $banner->save();
        return redirect()->route('banners.index')->with('message', 'Banner status updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        FileUpload::deleteFile($banner->image); 
        $banner->delete();
        return redirect()->route('banners.index')->with('message', 'Banner deleted successfully.');
    }
}
