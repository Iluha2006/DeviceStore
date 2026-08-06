<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function index()
    {

        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'imageUrl' => 'nullable|url',
            'quantity' => 'nullable|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100'
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return Product::create($request->all());
    }

public function byCategory($categoryId)
{
    $products = Product::where('category_id', $categoryId)->get();
    return response()->json($products);
}
    public function show($id)
{
    $product = Product::findOrFail($id);
    return response()->json($product);
}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return $product;
    }
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json();
    }


}
