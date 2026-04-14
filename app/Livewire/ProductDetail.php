<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Vanilo\Cart\Facades\Cart;
use Livewire\Component;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Product\Models\Product;

class ProductDetail extends Component
{
    public Product $product;

    public int $quantity = 1;

    public int $quantity = 1;
    public bool $added = false;

    public function mount(Product $product): void
    {
        $this->product = $product->load('media');
        $this->product = $product;
    }

    public function incrementQty(): void
    {
        $this->quantity++;
    }

    public function decrementQty(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): void
    {
        Cart::addItem($this->product, $this->quantity);
        $this->added = true;
        $this->dispatch('cartUpdated');
        $this->js("setTimeout(() => \$wire.set('added', false), 2500)");
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
