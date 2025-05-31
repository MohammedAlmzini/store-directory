<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        
        $stores = $query->latest()->paginate(12);
        $categories = Category::all();
        $cities = Store::select('city')->distinct()->whereNotNull('city')->get();
        
        return view('stores.index', compact('stores', 'categories', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $existingStore = Store::where('user_id', Auth::id())->first();
        if ($existingStore) {
            return redirect()->route('stores.edit', $existingStore)
                ->with('info', 'لديك متجر بالفعل. يمكنك تعديله هنا.');
        }
        
        $categories = Category::all();
        return view('stores.create', compact('categories'));
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
        
        return redirect()->route('dashboard')
            ->with('success', 'تم إنشاء المتجر بنجاح. سيتم مراجعته من قبل الإدارة قريباً.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        if ($store->status != 'approved' && 
            (!Auth::check() || (Auth::id() != $store->user_id && Auth::user()->role != 'admin'))) {
            abort(404);
        }
        
        $products = $store->products()->latest()->paginate(8);
        $reviews = $store->reviews()->with('user')->latest()->get();
        
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = Auth::user()->favorites()->where('store_id', $store->id)->exists();
        }
        
        return view('stores.show', compact('store', 'products', 'reviews', 'isFavorite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        if (Auth::id() != $store->user_id && Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $categories = Category::all();
        return view('stores.edit', compact('store', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        if (Auth::id() != $store->user_id && Auth::user()->role != 'admin') {
            abort(403);
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
        
        return redirect()->route('dashboard')
            ->with('success', 'تم تحديث معلومات المتجر بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        if (Auth::id() != $store->user_id && Auth::user()->role != 'admin') {
            abort(403);
        }
        
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }
        
        $store->delete();
        
        return redirect()->route('dashboard')
            ->with('success', 'تم حذف المتجر بنجاح.');
    }
    
 
    public function updateStatus(Request $request, Store $store)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $store->status = $request->status;
        $store->save();
        
        return redirect()->back()
            ->with('success', 'تم تحديث حالة المتجر بنجاح.');
    }
}