<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponController extends Controller
{
    public function coupons()
    {
        $coupons = Coupon::with('product:id,name,code,image')->where('is_active', true)->get();

        return response()->json([
            'coupons' => $coupons
        ]);
    }

    public function couponDetails($id)
    {
        $coupon = Coupon::with('product:id,name,code,image')->find($id);

        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found.'
            ], 404);
        }

        $totalCoupons = $coupon->total_coupons;
        $claimedCoupons = $coupon->claimed_count;

        $couponUsedPercent = $totalCoupons > 0 ? round(($claimedCoupons / $totalCoupons) * 100, 2) : 0;

        return response()->json([
            'coupon' => $coupon,
            'used_percent' => $couponUsedPercent
        ]);
    }

    public function index()
    {
        $coupons = Coupon::all();
        
        return Inertia::render('Coupons/Index', [
            'coupons' => $coupons,
        ]);
    }

    public function create()
    {
        $products = Product::select('id', 'name')->get();
        return Inertia::render('Coupons/Create', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'total_coupons' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'terms' => 'nullable|string',
        ]);

        Coupon::create([
            'product_id' => $request->product_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'discount_percent' => $request->discount_percent,
            'total_coupons' => $request->total_coupons,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'terms' => $request->terms 
        ]);

        return redirect()->route('coupons.index')->with('message', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $products = Product::select('id', 'name')->get();

        return Inertia::render('Coupons/Edit', [
            'coupon' => $coupon,
            'products' => $products
        ]);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'total_coupons' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'terms' => 'nullable|string',
        ]);

        $coupon->update([
            'product_id' => $request->product_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'discount_percent' => $request->discount_percent,
            'total_coupons' => $request->total_coupons,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'terms' => $request->terms 
        ]);

        return redirect()->route('coupons.index')->with('message', 'Coupon updated successfully.');
    }

    public function status($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = ! $coupon->is_active;
        $coupon->save();

        return redirect()->route('coupons.index')->with('message', 'Coupon status updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('coupons.index')->with('message', 'Coupon deleted successfully.');
    }
}
