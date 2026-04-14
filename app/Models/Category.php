<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'banner',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // ===== Relationships =====

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    // ===== Hierarchy helpers =====

    /**
     * Returns all ancestor categories from root down to (but not including) self.
     * Max depth of 5 to avoid infinite loops on bad data.
     */
    public function ancestors(): Collection
    {
        $ancestors = collect();
        $current = $this;
        $depth = 0;

        while ($current->parent_id && $depth < 5) {
            $current = $current->parent()->with('parent')->first();
            $ancestors->prepend($current);
            $depth++;
        }

        return $ancestors;
    }

    /**
     * Full breadcrumb trail: ancestors + self.
     */
    public function breadcrumb(): Collection
    {
        return $this->ancestors()->push($this);
    }

    /**
     * Returns true if this category has no parent (is a top-level category).
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    // ===== Scopes =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
