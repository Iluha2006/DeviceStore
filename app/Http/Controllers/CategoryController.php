<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }


    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoryname' => 'required|string|max:255|unique:categories'
        ]);

        $category = Category::create($validated);
        return response()->json($category, 201);
    }


    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'categoryname' => 'required|string|max:255|unique:categories,categoryname,'.$id
        ]);

        $category->update($validated);
        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(null, 204);
    }


    public function products($categoryId)
    {
        $category = Category::with('products')->findOrFail($categoryId);
        return response()->json($category->products);
    }
}