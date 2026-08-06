<?php

namespace App\Services;

use App\Models\Order;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class PaymentService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuth(
            config('services.yookassa.shop_id'), 
            config('services.yookassa.secret_key') 
        );
    }

    public function createPayment(float $amount, string $description, string $returnUrl, string $orderId)
    {
        $payment = $this->client->createPayment(
            [
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'payment_url' => $returnUrl,
                ],
                'capture' => true,
                'description' => $description,
                'metadata' => [
                    'order_id' => $orderId
                ],
            ],
            uniqid('', true)
        );

        return $payment;
    }

    public function handleNotification(array $data)
    {
        try {
            $notification = ($data['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
                ? new NotificationSucceeded($data)
                : new NotificationWaitingForCapture($data);

            $payment = $notification->getObject();
            $orderId = $payment->getMetadata()['order_id'];

            if ($payment->getStatus() === 'succeeded') {
                $order = Order::find($orderId);
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'paid']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Payment notification error: ' . $e->getMessage());
        }
    }
}