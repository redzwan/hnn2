<?php

namespace App\Livewire\Account;

use App\Models\Favorite;
use Livewire\Component;

class Favorites extends Component
{
    public function removeFavorite(int $favoriteId): void
    {
        Favorite::where('id', $favoriteId)
            ->where('user_id', auth()->id())
            ->delete();
    }

    public function render()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('product')
            ->latest()
            ->get();

        return view('livewire.account.favorites', compact('favorites'))
            ->layout('layouts.account');
    }
}
