<?php

namespace App\Http\Controllers;

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
        
        return view('admin.stores', compact('stores', 'status'));
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
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.users', compact('users', 'role', 'banned'));
    }

 
    public function banUser(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'لا يمكن حظر حساب مشرف');
        }
        
        $user->is_banned = true;
        $user->save();
        
        return redirect()->back()->with('success', 'تم حظر المستخدم بنجاح');
    }

 
    public function unbanUser(User $user)
    {
        $user->is_banned = false;
        $user->save();
        
        return redirect()->back()->with('success', 'تم إلغاء حظر المستخدم بنجاح');
    }


    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'لا يمكن حذف حساب مشرف');
        }
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الشخصي');
        }
        
        if ($user->store) {
            $user->store->delete();
        }
        
        $user->reviews()->delete();
        $user->favorites()->delete();
        
        $user->delete();
        
        return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}