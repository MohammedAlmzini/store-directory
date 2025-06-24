<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'vendor') {
            return $this->vendorDashboard();
        } else {
            return $this->userDashboard();
        }
    }
    
    private function adminDashboard()
    {
        $pendingStores = \App\Models\Store::where('status', 'pending')->count();
        $totalUsers = \App\Models\User::count();
        $totalStores = \App\Models\Store::count();
        $totalCategories = \App\Models\Category::count();
        
        return view('dashboard.admin', compact('pendingStores', 'totalUsers', 'totalStores', 'totalCategories'));
    }
    
    private function vendorDashboard()
    {
        $user = Auth::user();
        $store = \App\Models\Store::where('user_id', $user->id)->first();
        $products = $store ? \App\Models\Product::where('store_id', $store->id)->get() : [];
        $reviews = $store ? \App\Models\Review::where('store_id', $store->id)->get() : [];
        
        return view('dashboard.vendor', compact('store', 'products', 'reviews'));
        
    }
    
    private function userDashboard()
    {
        $user = Auth::user();
        $favorites = \App\Models\Favorite::where('user_id', $user->id)->with('store')->get();
        $reviews = \App\Models\Review::where('user_id', $user->id)->with('store')->get();
        
        return view('dashboard.user', compact('favorites', 'reviews'));
    }
}