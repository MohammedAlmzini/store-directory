<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
        $store = Store::where('user_id', Auth::id())->first();
        
        if (!$store) {
            return redirect()->route('stores.create')
                ->with('info', 'يجب إنشاء متجر أولاً قبل إضافة المنتجات.');
        }
        
        $products = Product::where('store_id', $store->id)->latest()->paginate(10);
        
        return view('products.index', compact('products', 'store'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $store = Store::where('user_id', Auth::id())->first();
        
        if (!$store) {
            return redirect()->route('stores.create')
                ->with('info', 'يجب إنشاء متجر أولاً قبل إضافة المنتجات.');
        }
        
        $categories = Category::all();
        
        return view('products.create', compact('store', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->first();
        
        if (!$store) {
            return redirect()->route('stores.create')
                ->with('error', 'يجب إنشاء متجر أولاً قبل إضافة المنتجات.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = $request->all();
        $data['store_id'] = $store->id;
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product_images', 'public');
        }
        
        Product::create($data);
        
        return redirect()->route('products.index')
            ->with('success', 'تمت إضافة المنتج بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if ($product->store->status != 'approved' && 
            (!Auth::check() || (Auth::id() != $product->store->user_id && Auth::user()->role != 'admin'))) {
            abort(404);
        }
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if (Auth::id() != $product->store->user_id && Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $categories = Category::all();
        
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (Auth::id() != $product->store->user_id && Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = $request->all();
        
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('product_images', 'public');
        }
        
        $product->update($data);
        
        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (Auth::id() != $product->store->user_id && Auth::user()->role != 'admin') {
            abort(403);
        }
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }

    /**
     * Display products for the vendor's store.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorProducts()
    {
        $store = Store::where('user_id', Auth::id())->first();
        
        if (!$store) {
            return redirect()->route('stores.create')
                ->with('info', 'يجب إنشاء متجر أولاً قبل إضافة المنتجات.');
        }
        
        $products = Product::where('store_id', $store->id)
            ->with('category')
            ->latest()
            ->paginate(10);
        
        return view('vendor.products', compact('products', 'store'));
    }
}