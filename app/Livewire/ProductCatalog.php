<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Vanilo\Cart\Facades\Cart;

class ProductCatalog extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'latest';

    public ?int $categoryId = null;

    public ?int $addedProductId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function filterByCategory(?int $id): void
    {
        $this->categoryId = $id;
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);
        Cart::addItem($product);
        $this->addedProductId = $productId;
        $this->dispatch('cartUpdated');

        $this->js("setTimeout(() => \$wire.set('addedProductId', null), 2000)");
    }

    public function render()
    {
        $query = Product::actives()->with('media');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $this->categoryId));
        }

        $query->when($this->sortBy === 'latest', fn ($q) => $q->latest())
            ->when($this->sortBy === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($this->sortBy === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($this->sortBy === 'name', fn ($q) => $q->orderBy('name'));

        $showCategoryPills = (bool) Setting::get('homepage.show_category_pills', true);

        return view('livewire.product-catalog', [
            'products' => $query->paginate(12),
            'categories' => $showCategoryPills ? Category::active()->roots()->orderBy('sort_order')->get() : collect(),
        ]);
    }
}
