<?php

namespace App\Livewire;

use App\Services\BillplzService;
use App\Services\PaypalService;
use Livewire\Component;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Foundation\Factories\OrderFactory;
use Vanilo\Shipment\Models\ShippingMethod;

class CheckoutForm extends Component
{
    public string $firstname = '';

    public string $lastname = '';

    public string $email = '';

    public string $phone = '';

    public string $address = '';

    public string $city = '';

    public string $state = '';

    public string $zip = '';

    public string $notes = '';

    public ?int $shippingMethodId = null;

    public string $paymentMethod = 'billplz';

    public bool $saveAsDefault = false;

    public bool $orderPlaced = false;

    public ?string $orderNumber = null;

    public function mount(): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            $nameParts = explode(' ', $user->name, 2);
            $this->firstname = $nameParts[0] ?? '';
            $this->lastname = $nameParts[1] ?? '';
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';

            if ($user->address) {
                $this->address = $user->address;
            }
            if ($user->default_city) {
                $this->city = $user->default_city;
            }
            if ($user->default_state) {
                $this->state = $user->default_state;
            }
            if ($user->default_zip) {
                $this->zip = $user->default_zip;
            }
        }

        $first = ShippingMethod::where('is_active', true)->first();
        if ($first) {
            $this->shippingMethodId = $first->id;
        }
    }

    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'shippingMethodId' => 'required|exists:shipping_methods,id',
            'paymentMethod' => 'required|in:billplz,paypal,cod',
        ];
    }

    public function getSelectedShippingMethodProperty(): ?ShippingMethod
    {
        if (! $this->shippingMethodId) {
            return null;
        }

        return ShippingMethod::find($this->shippingMethodId);
    }

    public function getShippingFeeProperty(): float
    {
        $method = $this->selectedShippingMethod;
        if (! $method) {
            return 0.0;
        }

        $cost = (float) ($method->configuration['cost'] ?? 0);
        $threshold = isset($method->configuration['free_threshold'])
            ? (float) $method->configuration['free_threshold']
            : null;

        if ($threshold !== null && Cart::total() >= $threshold) {
            return 0.0;
        }

        return $cost;
    }

    public function getTotalWithShippingProperty(): float
    {
        return (float) Cart::total() + $this->shippingFee;
    }

    public function placeOrder(): void
    {
        $this->validate();

        if (Cart::isEmpty()) {
            session()->flash('error', 'Your cart is empty.');

            return;
        }

        if ($this->saveAsDefault && auth()->check()) {
            auth()->user()->update([
                'phone' => $this->phone,
                'address' => $this->address,
                'default_city' => $this->city,
                'default_state' => $this->state,
                'default_zip' => $this->zip,
            ]);
        }

        // For BillPlz, validate the gateway is ready before creating the order
        if ($this->paymentMethod === 'billplz') {
            $billplz = app(BillplzService::class);

            if (! $billplz->isEnabled()) {
                $this->addError('paymentMethod', 'Online payment is currently unavailable. Please choose another payment method.');

                return;
            }
        }

        // For PayPal, validate the gateway is ready before creating the order
        if ($this->paymentMethod === 'paypal') {
            $paypal = app(PaypalService::class);

            if (! $paypal->isEnabled()) {
                $this->addError('paymentMethod', 'PayPal is currently unavailable. Please choose another payment method.');

                return;
            }
        }

        $cart = Cart::model();
        $subtotal = (float) Cart::total();
        $shippingFee = $this->shippingFee;
        $totalAmount = $subtotal + $shippingFee;

        $items = $cart->getItems()->map(function ($item) {
            return [
                'product' => $item->getBuyable(),
                'quantity' => $item->getQuantity(),
            ];
        })->all();

        $paymentLabel = match ($this->paymentMethod) {
            'cod' => 'Cash on Delivery',
            'paypal' => 'PayPal (AUD)',
            default => 'Online Payment (BillPlz)',
        };
        $notesWithPayment = trim(($this->notes ? $this->notes."\n" : '')."Payment: {$paymentLabel}");

        $orderData = [
            'billpayer' => [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => [
                    'address' => $this->address,
                    'city' => $this->city,
                    'postalcode' => $this->zip,
                    'country_id' => 'MY',
                ],
            ],
            'shipping_method_id' => $this->shippingMethodId,
            'notes' => $notesWithPayment,
        ];

        $factory = app(OrderFactory::class);
        $order = $factory->createFromDataArray($orderData, $items);

        // For BillPlz, create the bill before clearing the cart
        if ($this->paymentMethod === 'billplz') {
            try {
                $paymentUrl = app(BillplzService::class)->createBill(
                    $order,
                    "{$this->firstname} {$this->lastname}",
                    $this->email,
                    $this->phone,
                    $totalAmount,
                );

                Cart::destroy();
                $this->dispatch('cartUpdated');
                $this->redirect($paymentUrl, navigate: false);

                return;
            } catch (\Exception $e) {
                \Log::error('BillPlz bill creation failed', [
                    'order' => $order->getNumber(),
                    'error' => $e->getMessage(),
                ]);

                // Order exists but payment failed — show error, keep cart intact
                $this->addError('paymentMethod', 'Payment gateway error. Please try again or choose Cash on Delivery.');

                return;
            }
        }

        // PayPal — redirect to PayPal approval page
        if ($this->paymentMethod === 'paypal') {
            try {
                $approvalUrl = app(PaypalService::class)->createOrder(
                    $order,
                    $totalAmount,
                    route('paypal.return', ['order' => $order->getNumber()]),
                    route('paypal.cancel', ['order' => $order->getNumber()]),
                );

                Cart::destroy();
                $this->dispatch('cartUpdated');
                $this->redirect($approvalUrl, navigate: false);

                return;
            } catch (\Exception $e) {
                \Log::error('PayPal order creation failed', [
                    'order' => $order->getNumber(),
                    'error' => $e->getMessage(),
                ]);

                $this->addError('paymentMethod', 'PayPal error. Please try again or choose another payment method.');

                return;
            }
        }

        // COD — clear cart and show success
        Cart::destroy();
        $this->dispatch('cartUpdated');
        $this->orderPlaced = true;
        $this->orderNumber = $order->getNumber();
    }

    public function render()
    {
        return view('livewire.checkout-form', [
            'items' => Cart::model()?->items()->with('product')->get() ?? collect(),
            'subtotal' => Cart::total(),
            'shippingMethods' => ShippingMethod::where('is_active', true)->with('carrier')->get(),
        ]);
    }
}
