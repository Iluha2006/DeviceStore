<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;

class StripeCheckoutController extends Controller
{
    protected $stripeService;
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createCheckoutSession(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            if (!auth()->check() || auth()->id() !== $order->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }


          $session = $this->stripeService->createCheckoutSession($order);
            $order->update([
                'payment_session_id' => $session->id,
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'id' => $session->id,
                'url' => $session->url
            ]);

        } catch (\Exception  $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function success(Request $request)
{
    try {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return response()->json(['error' => 'Session ID is required'], 400);
        }

        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $order = Order::with('products')->findOrFail($session->metadata->order_id);


        $order->update(
        [
            'payment_status' => 'completed',
            'payment_session_id' => $sessionId
        ]);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['payment_status' => 'canceled']);

        return response()->json([
            'status' => 'canceled',
            'order' => $order
        ]);
    }


}