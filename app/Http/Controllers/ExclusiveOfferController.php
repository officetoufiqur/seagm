<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\ExclusiveOffer;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExclusiveOfferController extends Controller
{
    public function exclusiveOfferDetails($id)
    {
        $offer = ExclusiveOffer::find($id);

        if (!$offer) {
            return response()->json(['message' => 'Exclusive offer not found.'], 404);
        }

        $product = Product::find($offer->product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found for this exclusive offer.'], 404);
        }

        return response()->json(['data' => $product], 200);
    }

    public function index()
    {
        $offers = ExclusiveOffer::all();

        return Inertia::render('Exclusive/Index', [
            'offers' => $offers,
        ]);
    }

    public function create()
    {
        $products = Product::select('id', 'name')->get();

        return Inertia::render('Exclusive/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = FileUpload::storeFile($request->file('image'), 'uploads/exclusive-offers');
        }

        ExclusiveOffer::create([
            'product_id' => $request->product_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'discount_percent' => $request->discount_percent,
            'image' => $imagePath,
        ]);

        return redirect()->route('exclusive-offers.index')->with('message', 'Exclusive offer created successfully.');
    }

    public function edit($id)
    {
        $exclusiveOffer = ExclusiveOffer::findOrFail($id);
        $products = Product::select('id', 'name')->get();

        return Inertia::render('Exclusive/Edit', [
            'exclusiveOffer' => $exclusiveOffer,
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {
        $exclusiveOffer = ExclusiveOffer::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $exclusiveOffer->image;

        if ($request->hasFile('image')) {
            $imagePath = FileUpload::updateFile($request->file('image'), 'uploads/exclusive-offers', $exclusiveOffer->image);
        }

        $exclusiveOffer->update([
            'product_id' => $request->product_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'discount_percent' => $request->discount_percent,
            'image' => $imagePath,
        ]);

        return redirect()->route('exclusive-offers.index')->with('message', 'Exclusive offer updated successfully.');
    }

    public function destroy($id)
    {
        $exclusiveOffer = ExclusiveOffer::findOrFail($id);

        if ($exclusiveOffer->image) {
            FileUpload::deleteFile($exclusiveOffer->image);
        }

        $exclusiveOffer->delete();

        return redirect()->route('exclusive-offers.index')->with('message', 'Exclusive offer deleted successfully.');
    }

    public function status($id)
    {
        $exclusiveOffer = ExclusiveOffer::findOrFail($id);
 
        $exclusiveOffer->update([
            'is_active' => !$exclusiveOffer->is_active,
        ]);

        return redirect()->route('exclusive-offers.index')->with('message', 'Exclusive offer status updated successfully.');
    }
}
