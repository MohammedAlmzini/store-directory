<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::where('status', 'approved');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('city') && $request->city != 'all') {
            $query->where('city', $request->city);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $stores = $query->latest()->get();

        return response()->json([
            'message' => 'تم جلب المتاجر بنجاح',
            'data' => $stores
        ]);
    }

    public function show(Store $store)
    {
        if ($store->status != 'approved' && (Auth::id() != $store->user_id && Auth::user()?->role !== 'admin')) {
            return response()->json(['message' => 'المتجر غير متاح'], 404);
        }

        $store->load('products', 'reviews.user');

        return response()->json([
            'message' => 'تم جلب المتجر بنجاح',
            'data' => $store
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_info' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('store_logos', 'public');
        }

        $store = Store::create($data);

        return response()->json([
            'message' => 'تم إنشاء المتجر بنجاح. بانتظار المراجعة.',
            'data' => $store
        ], 201);
    }

    public function update(Request $request, Store $store)
    {
        if (Auth::id() != $store->user_id && Auth::user()->role != 'admin') {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_info' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $request->file('logo')->store('store_logos', 'public');
        }

        $store->update($data);

        return response()->json([
            'message' => 'تم تحديث المتجر بنجاح',
            'data' => $store
        ]);
    }

    public function destroy(Store $store)
    {
        if (Auth::id() != $store->user_id && Auth::user()->role != 'admin') {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        $store->delete();

        return response()->json([
            'message' => 'تم حذف المتجر بنجاح'
        ]);
    }

    public function myStore()
    {
        $store = Store::where('user_id', Auth::id())->first();

        if (!$store) {
            return response()->json([
                'message' => 'لا يوجد متجر مرتبط بهذا المستخدم'
            ], 404);
        }

        $store->load('products', 'reviews.user');

        return response()->json([
            'message' => 'تم جلب المتجر الخاص بك بنجاح',
            'data' => $store
        ]);
    }

    public function updateStatus(Request $request, Store $store)
    {
        if (Auth::user()->role != 'admin') {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $store->status = $request->status;
        $store->save();

        return response()->json([
            'message' => 'تم تحديث حالة المتجر بنجاح',
            'data' => $store
        ]);
    }
}
