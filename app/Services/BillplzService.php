<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Vanilo\Order\Contracts\Order;

class BillplzService
{
    protected string $apiKey;

    protected string $collectionId;

    protected string $xSignatureKey;

    protected bool $sandbox;

    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = Setting::get('billplz.api_key', '');
        $this->collectionId = Setting::get('billplz.collection_id', '');
        $this->xSignatureKey = Setting::get('billplz.x_signature_key', '');
        $this->sandbox = Setting::get('billplz.mode', 'sandbox') === 'sandbox';
        $this->baseUrl = $this->sandbox
            ? 'https://www.billplz-sandbox.com/api/v3'
            : 'https://www.billplz.com/api/v3';
    }

    public function isEnabled(): bool
    {
        return (bool) Setting::get('billplz.enabled', false)
            && ! empty($this->apiKey)
            && ! empty($this->collectionId);
    }

    /**
     * Create a BillPlz bill and return the payment URL.
     */
    public function createBill(Order $order, string $name, string $email, string $phone, float $amount): string
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->post("{$this->baseUrl}/bills", [
                'collection_id' => $this->collectionId,
                'email' => $email,
                'mobile' => $this->normalizePhone($phone),
                'name' => $name,
                'amount' => (int) round($amount * 100), // in cents
                'callback_url' => route('billplz.callback'),
                'redirect_url' => route('billplz.return', ['order' => $order->getNumber()]),
                'description' => 'Order #'.$order->getNumber(),
                'reference_1_label' => 'Order No',
                'reference_1' => $order->getNumber(),
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('BillPlz bill creation failed: '.$response->body());
        }

        return $response->json('url');
    }

    /**
     * Verify BillPlz X-Signature for webhook callbacks.
     */
    public function verifySignature(array $data): bool
    {
        if (empty($this->xSignatureKey)) {
            return true;
        }

        $signatureData = collect($data)
            ->except(['x_signature'])
            ->sortKeys()
            ->map(fn ($value, $key) => "{$key}|{$value}")
            ->implode('|');

        $expected = hash_hmac('sha256', $signatureData, $this->xSignatureKey);

        return hash_equals($expected, $data['x_signature'] ?? '');
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        if (str_starts_with($phone, '+')) {
            $phone = ltrim($phone, '+');
        } elseif (str_starts_with($phone, '0')) {
            $phone = '6'.$phone;
        }

        return $phone;
    }
}
