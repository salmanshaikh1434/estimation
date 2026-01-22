<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DsrItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'description',
        'unit',
        'rate_non_scheduled',
        'rate_scheduled',
        'dsr_type',
        'category',
        'sub_category',
        'remarks',
    ];

    protected $casts = [
        'rate_non_scheduled' => 'decimal:2',
        'rate_scheduled' => 'decimal:2',
    ];

    /**
     * Get the estimation items for this DSR item.
     */
    public function estimationItems(): HasMany
    {
        return $this->hasMany(EstimationItem::class);
    }

    /**
     * Scope a query to only include items of a given DSR type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('dsr_type', $type);
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
}
