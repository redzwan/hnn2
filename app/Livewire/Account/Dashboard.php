<?php

namespace App\Livewire\Account;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $orderCount = 0;
        $favoriteCount = $user->favorites()->count();
        $openComplaintsCount = $user->complaints()->where('status', 'open')->count();

        try {
            $orderCount = \Vanilo\Order\Models\Order::where('user_id', $user->id)->count();
            $recentOrders = \Vanilo\Order\Models\Order::where('user_id', $user->id)
                ->latest()->take(3)->get();
        } catch (\Throwable) {
            $recentOrders = collect();
        }

        return view('livewire.account.dashboard', compact('orderCount', 'favoriteCount', 'openComplaintsCount', 'recentOrders'))
            ->layout('layouts.account');
    }
}
