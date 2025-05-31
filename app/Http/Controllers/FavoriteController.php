<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('store')
            ->latest()
            ->paginate(12);
            
        return view('favorites.index', compact('favorites'));
    }
    
 
    public function add(Store $store)
    {
        $existing = Favorite::where('user_id', Auth::id())
            ->where('store_id', $store->id)
            ->first();
            
        if (!$existing) {
            Favorite::create([
                'user_id' => Auth::id(),
                'store_id' => $store->id
            ]);
            
            return redirect()->back()
                ->with('success', 'تمت إضافة المتجر إلى المفضلة.');
        }
        
        return redirect()->back()
            ->with('info', 'هذا المتجر موجود بالفعل في المفضلة.');
    }
    

    public function remove(Store $store)
    {
        Favorite::where('user_id', Auth::id())
            ->where('store_id', $store->id)
            ->delete();
            
        return redirect()->back()
            ->with('success', 'تمت إزالة المتجر من المفضلة.');
    }
}