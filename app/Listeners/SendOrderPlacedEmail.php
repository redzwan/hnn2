<?php

namespace App\Listeners;

use App\Mail\TemplateMail;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Vanilo\Order\Events\OrderWasCreated;

class SendOrderPlacedEmail
{
    public function handle(OrderWasCreated $event): void
    {
        try {
            $order = $event->getOrder();
            $billpayer = $order->getBillpayer();

            $variables = [
                'customer_name' => $billpayer->getName(),
                'customer_email' => $billpayer->getEmail(),
                'order_number' => $order->getNumber(),
                'order_date' => $order->created_at->format('d M Y'),
                'order_total' => number_format($order->total(), 2),
                'order_status' => ucfirst($order->status->value()),
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
            ];

            // Email to customer
            $customerTemplate = EmailTemplate::findByKey('order_placed');
            if ($customerTemplate && $billpayer->getEmail()) {
                Mail::to($billpayer->getEmail())->send(new TemplateMail($customerTemplate, $variables));
            }

            // Email to admin
            $adminTemplate = EmailTemplate::findByKey('order_placed_admin');
            $adminEmail = Setting::get('mail_from_address');
            if ($adminTemplate && $adminEmail) {
                Mail::to($adminEmail)->send(new TemplateMail($adminTemplate, $variables));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send order email', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
