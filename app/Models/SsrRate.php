<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SsrRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'description',
        'unit',
        'rate_non_scheduled',
        'rate_scheduled',
        'category',
        'sub_category',
        'remarks',
    ];

    protected $casts = [
        'rate_non_scheduled' => 'decimal:2',
        'rate_scheduled' => 'decimal:2',
    ];

    /**
     * Get all estimation items that use this SSR rate.
     */
    public function estimationItems(): MorphMany
    {
        return $this->morphMany(EstimationItem::class, 'rate');
    }

    /**
     * Scope a query to search items by keyword.
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('item_code', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
