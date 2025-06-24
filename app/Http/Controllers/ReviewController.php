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
        // عرض جميع المراجعات (للمسؤول)
        $reviews = Review::with(['user', 'store'])->latest()->paginate(15);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // صفحة إنشاء مراجعة جديدة
        $stores = Store::where('status', 'approved')->get();
        return view('reviews.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // التحقق من عدم وجود مراجعة سابقة لنفس المتجر من نفس المستخدم
        $existingReview = Review::where('user_id', Auth::id())
            ->where('store_id', $request->store_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'لقد قمت بتقييم هذا المتجر مسبقاً، يمكنك تعديل تقييمك الحالي.');
        }

        // إنشاء مراجعة جديدة
        $review = new Review();
        $review->user_id = Auth::id();
        $review->store_id = $request->store_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->back()->with('success', 'تم إضافة التقييم بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        // التحقق من أن المراجعة تنتمي للمستخدم الحالي
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'غير مسموح لك بتعديل هذه المراجعة');
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        // التحقق من أن المراجعة تنتمي للمستخدم الحالي
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'غير مسموح لك بتعديل هذه المراجعة');
        }

        // التحقق من البيانات
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // تحديث المراجعة
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->back()->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        // التحقق من أن المراجعة تنتمي للمستخدم الحالي أو المستخدم مسؤول
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'غير مسموح لك بحذف هذه المراجعة');
        }

        $review->delete();
        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * Display vendor reviews.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorReviews(Request $request)
    {
        // التحقق من أن المستخدم له متجر
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('dashboard')->with('error', 'ليس لديك متجر بعد');
        }

        // جلب المراجعات الخاصة بالمتجر
        $query = Review::where('store_id', $store->id);
        
        // تطبيق تصفية حسب التقييم إذا تم تحديده
        if ($request->has('rating') && $request->rating != 'all') {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->get();
        return view('vendor.reviews', compact('reviews'));
    }

    /**
     * Display user reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function userReviews()
    {
        // جلب مراجعات المستخدم الحالي
        $reviews = Review::where('user_id', Auth::id())
            ->with('store')
            ->latest()
            ->paginate(10);
        
        return view('user.reviews', compact('reviews'));
    }

    /**
     * Show form for editing user review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function editUserReview(Review $review)
    {
        // التحقق من أن المراجعة تنتمي للمستخدم الحالي
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('user.reviews')->with('error', 'غير مسموح لك بتعديل هذه المراجعة');
        }

        return view('user.reviews.edit', compact('review'));
    }

    /**
     * Update user review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function updateUserReview(Request $request, Review $review)
    {
        // التحقق من أن المراجعة تنتمي للمستخدم الحالي
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('user.reviews')->with('error', 'غير مسموح لك بتعديل هذه المراجعة');
        }

        // التحقق من البيانات
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // تحديث المراجعة
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->route('user.reviews')->with('success', 'تم تحديث التقييم بنجاح');
    }
}