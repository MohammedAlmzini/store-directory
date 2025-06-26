<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'تم جلب الفئات بنجاح',
            'data' => $categories
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'message' => 'تم جلب الفئة بنجاح',
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories'
        ]);

        $category = Category::create($request->only('name'));

        return response()->json([
            'message' => 'تم إنشاء الفئة بنجاح',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update($request->only('name'));

        return response()->json([
            'message' => 'تم تحديث الفئة بنجاح',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'تم حذف الفئة بنجاح'
        ]);
    }
}