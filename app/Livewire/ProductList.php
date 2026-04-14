<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Vanilo\Cart\Facades\Cart;

class ProductList extends Component
{
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        Cart::addItem($product);
        $this->dispatch('cartUpdated'); // Refresh cart count
    }

    public function render()
    {
        return view('livewire.product-list', [
            'products' => Product::actives()->with('media')->get(),
        ]);
    }
}
