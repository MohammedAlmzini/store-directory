<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\Category;

class DashboardController extends Controller
{
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
        return response()->json([
            'pending_stores' => Store::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_stores' => Store::count(),
            'total_categories' => Category::count(),
        ]);
    }

    private function vendorDashboard()
    {
        $user = Auth::user();
        $store = Store::where('user_id', $user->id)->first();

        return response()->json([
            'store' => $store,
            'products' => $store ? Product::where('store_id', $store->id)->get() : [],
            'reviews' => $store ? Review::where('store_id', $store->id)->get() : [],
        ]);
    }

    private function userDashboard()
    {
        $user = Auth::user();

        return response()->json([
            'favorites' => Favorite::where('user_id', $user->id)->with('store')->get(),
            'reviews' => Review::where('user_id', $user->id)->with('store')->get(),
        ]);
    }
}
