<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Vanilo\Cart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Product\Models\Product;

class ProductCatalog extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'latest';

    public string $sortBy = 'latest';
    public ?int $addedProductId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);
        Cart::addItem($product);
        $this->addedProductId = $productId;
        $this->dispatch('cartUpdated');

        // Reset the "added" state after 2 seconds
        $this->js("setTimeout(() => \$wire.set('addedProductId', null), 2000)");
    }

    public function render()
    {
        $query = Product::actives()->with('media');
        $query = Product::actives();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        $query->when($this->sortBy === 'latest', fn ($q) => $q->latest())
            ->when($this->sortBy === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($this->sortBy === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($this->sortBy === 'name', fn ($q) => $q->orderBy('name'));
                  ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        $query->when($this->sortBy === 'latest', fn($q) => $q->latest())
              ->when($this->sortBy === 'price_asc', fn($q) => $q->orderBy('price'))
              ->when($this->sortBy === 'price_desc', fn($q) => $q->orderByDesc('price'))
              ->when($this->sortBy === 'name', fn($q) => $q->orderBy('name'));

        return view('livewire.product-catalog', [
            'products' => $query->paginate(12),
        ]);
    }
}
