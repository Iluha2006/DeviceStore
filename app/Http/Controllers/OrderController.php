<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{



    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['products' => function($query) {
                $query->select('products.id', 'name', 'imageUrl')
                ->withPivot(['quantity', 'price']);

            }])->select(
            [
                'id',
                'user_id',
                'total_amount',
                'discount',
                'status',
                'comment',
                'created_at',
                'updated_at'
            ])
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total_amount' => (float) $order->total_amount,
                    'discount' => (float) $order->discount,
                    'status' => $order->status,
                    'comment' => $order->comment,
                    'created_at' => $order->created_at->toDateTimeString(),
                    'products' => $order->products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'imageUrl' => $product->imageUrl,
                            'quantity' => $product->pivot->quantity,
                            'price' => (float) $product->pivot->price
                        ];
                    })
                ];
            });

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Ваша корзина пуста'], 400);
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = $user->orders()->create(
            [
            'total_amount' => $totalAmount,
            'discount' => 0,
            'status' => 'pending',
            'comment' => $request->comment
        ]);




        foreach ($cartItems as $item) {
            if ($item->quantity > 0) {
                $item->product->decrement('quantity', $item->quantity);
            }

            $order->products()->attach($item->product_id,
            [
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        $user->cartItems()->delete();
        $stripeService = new StripeService();
        $session = $stripeService->createCheckoutSession($order);


        return response()->json([
            'order' => [
                'id' => $order->id,
                'total_amount' => (float) $order->total_amount,
                'created_at' => $order->created_at->toDateTimeString(),
                'status' => $order->status,
                'payment_url' => $session->url
            ]
        ], 201);
    }


    public function show($id)
    {
        $order = Auth::user()->orders()
            ->with(['products' => function($query) {
                $query->select('products.id', 'name', 'imageUrl')
                    ->withPivot(['quantity', 'price']);
            }])
            ->select([
                'id',
                'user_id',
                'total_amount',
                'discount',
                'status',
                'comment',
                'created_at',
                'updated_at'
            ])
            ->findOrFail($id);

        return response()->json([
            'id' => $order->id,
            'total_amount' => (float) $order->total_amount,
            'discount' => (float) $order->discount,
            'status' => $order->status,
            'comment' => $order->comment,
            'created_at' => $order->created_at->toDateTimeString(),
            'products' => $order->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'imageUrl' => $product->imageUrl,
                    'quantity' => $product->pivot->quantity,
                    'price' => (float) $product->pivot->price
                ];
            })
        ]);
    }
}