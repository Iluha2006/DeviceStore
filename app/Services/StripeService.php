<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }


    public function createCheckoutSession(Order $order)
{
    $lineItems = $order->products->map(function ($product) {
        return [
            'price_data' => [
                'currency' => 'rub',
                'product_data' => [
                    'name' => $product->name,

                ],
                'unit_amount' => $product->pivot->price * 100,
            ],
            'quantity' => $product->pivot->quantity,
        ];

        if (!empty($product->imageUrl)) {
            $productData['images'] = [$product->imageUrl];
        }
    })->toArray();

    return Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => config('app.frontend_url') . '/payment/success?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => config('app.frontend_url') . '/payment/cancel?session_id={CHECKOUT_SESSION_ID}',
        'metadata' => [
            'order_id' => $order->id
        ],
    ]);
}
}