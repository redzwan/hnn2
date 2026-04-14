<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Vanilo\Cart\Facades\Cart;

class CartPage extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function removeItem(int $itemId): void
    {
        Cart::removeItem(Cart::model()->items->firstWhere('id', $itemId));
        $this->dispatch('cartUpdated');
    }

    public function updateQty(int $itemId, int $qty): void
    {
        if ($qty < 1) {
            $this->removeItem($itemId);

            return;
        }

        $item = Cart::model()->items->firstWhere('id', $itemId);
        if ($item) {
            $item->update(['quantity' => $qty]);
        }

        $this->dispatch('cartUpdated');
    }

    public function clearCart(): void
    {
        Cart::clear();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $cart = Cart::model();
        $items = $cart ? $cart->items()->with('product')->get() : collect();

        // Re-hydrate products through App\Models\Product so Spatie media
        // morph types match (avoids hasMedia() always returning false)
        if ($items->isNotEmpty()) {
            $productIds = $items->pluck('product_id')->filter()->unique()->values();
            $appProducts = Product::with('media')->whereIn('id', $productIds)->get()->keyBy('id');

            $items->each(function ($item) use ($appProducts) {
                if ($item->product_id && $appProducts->has($item->product_id)) {
                    $item->setRelation('product', $appProducts->get($item->product_id));
                }
            });
        }

        return view('livewire.cart-page', [
            'items' => $items,
            'total' => Cart::total(),
            'isEmpty' => Cart::isEmpty(),
        ]);
    }
}
