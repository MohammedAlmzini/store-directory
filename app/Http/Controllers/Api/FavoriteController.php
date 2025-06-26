<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())->with('store')->get();

        return response()->json($favorites);
    }

    public function add(Store $store)
    {
        $user = Auth::user();

        $exists = Favorite::where('user_id', $user->id)->where('store_id', $store->id)->exists();

        if ($exists) {
            return response()->json(['message' => 'المتجر موجود بالفعل في المفضلة.'], 400);
        }

        Favorite::create([
            'user_id' => $user->id,
            'store_id' => $store->id,
        ]);

        return response()->json(['message' => 'تمت الإضافة إلى المفضلة.']);
    }

    public function remove(Store $store)
    {
        Favorite::where('user_id', Auth::id())->where('store_id', $store->id)->delete();

        return response()->json(['message' => 'تمت الإزالة من المفضلة.']);
    }
}
