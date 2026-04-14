<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public function render()
    {
        try {
            $orders = \Vanilo\Order\Models\Order::where('user_id', auth()->id())
                ->latest()
                ->paginate(10);
        } catch (\Throwable) {
            $orders = collect();
        }

        return view('livewire.account.orders', compact('orders'))
            ->layout('layouts.account');
    }
}
