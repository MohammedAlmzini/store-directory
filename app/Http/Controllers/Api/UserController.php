<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return response()->json([
            'message' => 'تم جلب المستخدمين بنجاح',
            'data' => $users
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'message' => 'تم جلب بيانات المستخدم',
            'data' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'تم حذف المستخدم بنجاح'
        ]);
    }
}
