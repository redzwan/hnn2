<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Vanilo\Foundation\Models\Order;

class PaypalService
{
    private string $baseUrl;

    private string $clientId;

    private string $clientSecret;

    private string $currency = 'AUD';

    public function __construct()
    {
        $mode = Setting::get('paypal.mode', 'sandbox');
        $this->baseUrl = $mode === 'production'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
        $this->clientId = Setting::get('paypal.client_id', '');
        $this->clientSecret = Setting::get('paypal.client_secret', '');
    }

    public function isEnabled(): bool
    {
        return (bool) Setting::get('paypal.enabled', false)
            && ! empty($this->clientId)
            && ! empty($this->clientSecret);
    }

    private function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        $token = $response->json('access_token');

        if (! $token) {
            throw new \RuntimeException('PayPal authentication failed.');
        }

        return $token;
    }

    public function createOrder(Order $order, float $amount, string $returnUrl, string $cancelUrl): string
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $order->getNumber(),
                    'amount' => [
                        'currency_code' => $this->currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name' => config('app.name'),
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        $approvalUrl = collect($response->json('links'))
            ->firstWhere('rel', 'approve')['href'] ?? null;

        if (! $approvalUrl) {
            throw new \RuntimeException('PayPal order creation failed: no approval URL returned.');
        }

        return $approvalUrl;
    }

    public function captureOrder(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture");

        return $response->json();
    }
}
