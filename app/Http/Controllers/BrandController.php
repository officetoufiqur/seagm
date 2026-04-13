<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Brand;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();

        return Inertia::render('Brand/Index', compact('brands'));
    }

    public function create()
    {
        return Inertia::render('Brand/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $page = Page::where('slug', 'brands')->first();

        $file = null;

        if ($request->hasFile('logo')) {
            $file = FileUpload::storeFile($request->file('logo'), 'uploads/brands');
        }

        $brand = new Brand;
        $brand->page_id = $page->id;
        $brand->logo = $file;
        $brand->save();

        return redirect()->route('brands.index')->with('message', 'Brand created successfully.');
    }

    public function edit($id)
    {
        $brand = Brand::find($id);

        return Inertia::render('Brand/Edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $brand = Brand::find($id);

        $file = $brand->logo;

        if ($request->hasFile('logo')) {
            $file = FileUpload::updateFile($request->file('logo'), $brand->logo, 'uploads/brands');
        }

        $brand->logo = $file;
        $brand->save();

        return redirect()->route('brands.index')->with('message', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if ($brand->logo) {
            FileUpload::deleteFile($brand->logo);
        }
        $brand->delete();

        return redirect()->route('brands.index')->with('message', 'Brand deleted successfully.');
    }
}
