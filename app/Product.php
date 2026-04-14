<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vanilo\Foundation\Models\Product as BaseProduct;

class Product extends BaseProduct
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
