<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin'); 
    }

    public function stores(Request $request)
    {
        $status = $request->status ?? 'all';

        $query = Store::with('user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $stores = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'status' => $status,
            'data' => $stores
        ]);
    }

    public function users(Request $request)
    {
        $role = $request->role ?? 'all';
        $banned = $request->has('banned') ? (bool)$request->banned : null;

        $query = User::query();

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($banned !== null) {
            $query->where('is_banned', $banned);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'role' => $role,
            'banned' => $banned,
            'data' => $users
        ]);
    }

    public function banUser(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['success' => false, 'message' => 'لا يمكن حظر حساب مشرف'], 403);
        }

        $user->is_banned = true;
        $user->save();

        return response()->json(['success' => true, 'message' => 'تم حظر المستخدم بنجاح']);
    }

    public function unbanUser(User $user)
    {
        $user->is_banned = false;
        $user->save();

        return response()->json(['success' => true, 'message' => 'تم إلغاء حظر المستخدم بنجاح']);
    }

    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['success' => false, 'message' => 'لا يمكن حذف حساب مشرف'], 403);
        }

        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك حذف حسابك الشخصي'], 403);
        }

        if ($user->store) {
            $user->store->delete();
        }

        $user->reviews()->delete();
        $user->favorites()->delete();

        $user->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف المستخدم بنجاح']);
    }
}
