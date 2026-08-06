<?php


namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }

    public function createCategory(array $data)
    {
        $validator = Validator::make($data, [
            'categoryname' => 'required|string|max:255|unique:categories'
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($data, [
            'categoryname' => 'required|string|max:255|unique:categories,categoryname,'.$id
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $category->update($data);
        return $category;
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
    }

    public function getCategoryProducts($categoryId)
    {
        return Category::with('products')->findOrFail($categoryId)->products;
    }
}