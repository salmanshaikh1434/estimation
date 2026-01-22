<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimationMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimation_item_id',
        'row_number',
        'length',
        'breadth',
        'height',
        'number',
        'quantity',
        'remarks',
        'sort_order',
    ];

    protected $casts = [
        'length' => 'decimal:3',
        'breadth' => 'decimal:3',
        'height' => 'decimal:3',
        'quantity' => 'decimal:3',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::saving(function ($measurement) {
            $measurement->calculateQuantity();
        });
    }

    /**
     * Calculate quantity based on dimensions.
     */
    public function calculateQuantity(): void
    {
        $this->quantity = ($this->length ?? 1) * 
                         ($this->breadth ?? 1) * 
                         ($this->height ?? 1) * 
                         ($this->number ?? 1);
    }

    /**
     * Get the estimation item that owns the measurement.
     */
    public function estimationItem(): BelongsTo
    {
        return $this->belongsTo(EstimationItem::class);
    }
}
