<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
 
    public function edit()
    {
        $user = Auth::user();
        return view('account.settings', compact('user'));
    }
    

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        return redirect()->back()->with('success', 'تم تحديث معلومات الحساب بنجاح');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->back()->with('success', 'تم تحديث كلمة المرور بنجاح');
    }
}