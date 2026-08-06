<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;

    }


    public function productIndex()
    {
        return response()->json($this->productService->getAllProducts());
    }

    public function productStore(Request $request)
    {
        try {
            $product = $this->productService->createProduct($request->all());
            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }

    public function productShow($id)
    {
        return response()->json($this->productService->getProductById($id));
    }

    public function productUpdate(Request $request, $id)
    {
        try {
            $product = $this->productService->updateProduct($id, $request->all());
            return response()->json($product);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }

    public function productDestroy($id)
    {
        $this->productService->deleteProduct($id);
        return response()->json(null, 204);
    }

    public function categoryIndex()
    {
        return response()->json($this->categoryService->getAllCategories());
    }

    public function categoryStore(Request $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->all());
            return response()->json($category, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }

    public function categoryShow($id)
    {
        return response()->json($this->categoryService->getCategoryById($id));
    }

    public function categoryUpdate(Request $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->all());
            return response()->json($category);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }

    public function categoryDestroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return response()->json(null, 204);
    }
    public function categoryProducts($categoryId)
    {
        return response()->json($this->categoryService->getCategoryProducts($categoryId));
    }
}