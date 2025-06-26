<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('store', 'category')->latest()->get();
        return response()->json(['message' => 'تم جلب المنتجات بنجاح', 'data' => $products]);
    }

    public function show(Product $product)
    {
        return response()->json(['message' => 'تم جلب المنتج بنجاح', 'data' => $product->load('store', 'category')]);
    }

    public function vendorProducts()
    {
        $store = Store::where('user_id', Auth::id())->first();
        if (!$store) {
            return response()->json(['message' => 'يجب إنشاء متجر أولاً'], 403);
        }

        $products = Product::where('store_id', $store->id)->with('category')->latest()->get();
        return response()->json(['message' => 'منتجات المورد', 'data' => $products]);
    }

    public function store(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->first();
        if (!$store) {
            return response()->json(['message' => 'يجب إنشاء متجر أولاً'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only('name', 'price', 'category_id', 'description');
        $data['store_id'] = $store->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product_images', 'public');
        }

        $product = Product::create($data);

        return response()->json(['message' => 'تم إنشاء المنتج بنجاح', 'data' => $product], 201);
    }

    public function update(Request $request, Product $product)
    {
        $store = Store::where('user_id', Auth::id())->first();
        if (!$store || $store->id !== $product->store_id) {
            return response()->json(['message' => 'ليس لديك صلاحية تعديل هذا المنتج'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only('name', 'price', 'category_id', 'description');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('product_images', 'public');
        }

        $product->update($data);

        return response()->json(['message' => 'تم تحديث المنتج بنجاح', 'data' => $product]);
    }

    public function destroy(Product $product)
    {
        $store = Store::where('user_id', Auth::id())->first();
        if (!$store || $store->id !== $product->store_id) {
            return response()->json(['message' => 'ليس لديك صلاحية حذف هذا المنتج'], 403);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'تم حذف المنتج بنجاح']);
    }
}
