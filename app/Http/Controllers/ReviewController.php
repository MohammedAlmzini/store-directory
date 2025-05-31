<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('store')
            ->latest()
            ->paginate(10);
            
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        
        $existingReview = Review::where('user_id', Auth::id())
            ->where('store_id', $request->store_id)
            ->first();
            
        if ($existingReview) {
            $existingReview->rating = $request->rating;
            $existingReview->comment = $request->comment;
            $existingReview->save();
            
            return redirect()->back()
                ->with('success', 'تم تحديث تقييمك بنجاح.');
        }
        
        Review::create([
            'user_id' => Auth::id(),
            'store_id' => $request->store_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        
        return redirect()->back()
            ->with('success', 'تم إضافة تقييمك بنجاح. شكراً لمشاركتك.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        if (Auth::id() != $review->user_id && Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $review->delete();
        
        return redirect()->back()
            ->with('success', 'تم حذف التقييم بنجاح.');
    }
}