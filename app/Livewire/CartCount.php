<?php

namespace App\Livewire;

use Livewire\Component;
use Vanilo\Cart\Facades\Cart;

class CartCount extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        return view('livewire.cart-count', [
            'count' => Cart::itemCount(),
        ]);
    }
}
