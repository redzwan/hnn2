<?php

namespace App\Http\Controllers;

use App\Services\PaypalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vanilo\Foundation\Models\Order;
use Vanilo\Order\Models\OrderStatus;

class PaypalController extends Controller
{
    public function __construct(protected PaypalService $paypal) {}

    /**
     * User returns from PayPal after approving payment.
     * Captures the payment and updates order status.
     */
    public function paymentReturn(Request $request, string $order)
    {
        $paypalOrderId = $request->query('token');
        $payerId = $request->query('PayerID');

        if (! $paypalOrderId || ! $payerId) {
            return view('checkout.paypal-return', [
                'orderNumber' => $order,
                'paid' => false,
                'error' => 'Payment was cancelled or incomplete.',
            ]);
        }

        try {
            $capture = $this->paypal->captureOrder($paypalOrderId);
            $status = $capture['status'] ?? null;
            $paid = $status === 'COMPLETED';

            if ($paid) {
                $orderModel = Order::where('number', $order)->first();

                if ($orderModel && $orderModel->status->value() !== 'processing') {
                    $orderModel->status = OrderStatus::PROCESSING;
                    $orderModel->save();
                }
            }

            return view('checkout.paypal-return', [
                'orderNumber' => $order,
                'paid' => $paid,
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal capture failed', [
                'order' => $order,
                'paypal_order_id' => $paypalOrderId,
                'error' => $e->getMessage(),
            ]);

            return view('checkout.paypal-return', [
                'orderNumber' => $order,
                'paid' => false,
                'error' => 'Payment capture failed. Please contact support.',
            ]);
        }
    }

    /**
     * User cancelled payment on PayPal.
     */
    public function paymentCancel(Request $request, string $order)
    {
        return view('checkout.paypal-return', [
            'orderNumber' => $order,
            'paid' => false,
            'error' => 'You cancelled the PayPal payment.',
        ]);
    }
}
