<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'store')->latest()->get();

        return response()->json([
            'message' => 'تم جلب التقييمات بنجاح',
            'data' => $reviews
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'store_id' => $request->store_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'تم إضافة التقييم بنجاح',
            'data' => $review
        ], 201);
    }

    public function update(Request $request, Review $review)
    {
        if (Auth::id() != $review->user_id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'تم تعديل التقييم بنجاح',
            'data' => $review
        ]);
    }

    public function destroy(Review $review)
    {
        if (Auth::id() != $review->user_id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'تم حذف التقييم بنجاح']);
    }

    public function userReviews()
    {
        $reviews = Review::where('user_id', Auth::id())->with('store')->latest()->get();

        return response()->json([
            'message' => 'تم جلب تقييماتك بنجاح',
            'data' => $reviews
        ]);
    }

    public function vendorReviews()
    {
        $store = Auth::user()->store;
        if (!$store) {
            return response()->json(['message' => 'لا يوجد متجر مرتبط'], 404);
        }

        $reviews = $store->reviews()->with('user')->latest()->get();

        return response()->json([
            'message' => 'تم جلب تقييمات المتجر',
            'data' => $reviews
        ]);
    }
}
