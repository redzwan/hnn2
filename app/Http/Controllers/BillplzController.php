<?php

namespace App\Http\Controllers;

use App\Services\BillplzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vanilo\Foundation\Models\Order;
use Vanilo\Order\Models\OrderStatus;

class BillplzController extends Controller
{
    public function __construct(protected BillplzService $billplz) {}

    /**
     * User returns from BillPlz after payment.
     * BillPlz appends ?billplz[id]=...&billplz[paid]=true to the redirect URL.
     */
    public function paymentReturn(Request $request, string $order)
    {
        $billplzData = $request->query('billplz', []);
        $paid = filter_var($billplzData['paid'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $billId = $billplzData['id'] ?? null;

        return view('checkout.billplz-return', [
            'orderNumber' => $order,
            'paid' => $paid,
            'billId' => $billId,
        ]);
    }

    /**
     * BillPlz webhook callback — verifies X-Signature and updates order status.
     */
    public function callback(Request $request)
    {
        $data = $request->all();

        if (! $this->billplz->verifySignature($data)) {
            Log::warning('BillPlz webhook signature mismatch', $data);

            return response('Invalid signature', 403);
        }

        $orderNumber = $data['reference_1'] ?? null;
        $paid = filter_var($data['paid'] ?? false, FILTER_VALIDATE_BOOLEAN);

        Log::info('BillPlz webhook received', [
            'order' => $orderNumber,
            'paid' => $paid,
            'bill_id' => $data['id'] ?? null,
        ]);

        if ($orderNumber && $paid) {
            $order = Order::where('number', $orderNumber)->first();

            if ($order && $order->status->value() !== 'processing') {
                try {
                    $order->status = OrderStatus::PROCESSING;
                    $order->save();
                } catch (\Exception $e) {
                    Log::error('Failed to update order status after BillPlz payment', [
                        'order' => $orderNumber,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return response('OK', 200);
    }
}
