<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        return response()->json(
            auth()->user()->favorites()->with('product')->get()
        );
    }

    public function addToFavorites(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = auth()->id();
        $productId = $request->product_id;

        $favorite = Favorite::firstOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId
            ],
            [
                'user_id' => $userId,
                'product_id' => $productId
            ]
        );
        if (!$favorite->wasRecentlyCreated) {
            return response()->json([
                'message' => 'Товар уже в избранном',
                'is_favorite' => true,
                'favorite' => $favorite->load('product')
            ], 200);
        }

        return response()->json([
            'message' => 'Товар добавлен в избранное',
            'favorite' => $favorite->load('product'),
            'is_favorite' => true
        ], 201);
    }


public function deleteFavorite($productId)
{
    $favorite = auth()->user()
                    ->favorites()
                    ->where('product_id', $productId)
                    ->firstOrFail();

    $favorite->delete();
    return response()->json([
        'success' => true,
        'message' => 'Товар удален из избранного',
        'is_favorite' => false
    ]);
}

 public function clearAll()  {

    auth()->user()->favorites()->delete();
    return response()->json();
 }
}