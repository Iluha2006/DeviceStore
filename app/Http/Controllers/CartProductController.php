<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartProductController extends Controller
{


    public function index()
    {

        return response()->json(
            auth()->user()->cartItems()->with('product')->get()
        );
    }

    public function addToCart(Request $request)
    {


        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $cartItem = auth()->user()->cartItems()->firstOrNew([
            'product_id' => $request->product_id
        ]);

        $cartItem->quantity = $cartItem->exists ? 
        $cartItem->quantity + ($request->quantity ?? 1)
            : ($request->quantity ?? 1);

        $cartItem->save();
        return response()->json($cartItem->load('product'));
    }

    public function increment($id)
    {


        $cartItem = auth()->user()->cartItems()
        ->with('product')
        ->findOrFail($id);
        if ($cartItem->quantity >= $cartItem->product->quantity) {
            return response()->json([
                'available' => $cartItem->product->quantity
            ], 422);
        }
        $cartItem->increment('quantity');
        return response()->json($cartItem->load('product'));
    }

    public function decrement($id)
    {

       $cartItem = auth()->user()
       ->cartItems()->with('product')->findOrFail($id);

        if ($cartItem->quantity <= 1) {
            return response()->json(
                 422);
        }

        $cartItem->decrement('quantity');
        return response()->json($cartItem->load('product'));
    }

    public function removeFromCart($id)
    {

        $cartItem = auth()->user()->cartItems()->findOrFail($id);
        $cartItem->delete();
        return response()->json(['success' => true]);
    }
}