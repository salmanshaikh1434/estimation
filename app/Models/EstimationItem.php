<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EstimationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimation_id',
        'rate_id',
        'rate_type',
        'quantity',
        'rate',
        'amount',
        'remarks',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the estimation that owns the item.
     */
    public function estimation(): BelongsTo
    {
        return $this->belongsTo(Estimation::class);
    }

    /**
     * Get the rate (DSR, SSR, or WRD) for this estimation item.
     * This is a polymorphic relationship.
     */
    public function rate(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the measurements for this estimation item.
     */
    public function measurements()
    {
        return $this->hasMany(EstimationMeasurement::class)->orderBy('sort_order');
    }

    /**
     * Get the calculation formula for this item.
     */
    public function calculationFormula(): BelongsTo
    {
        return $this->belongsTo(CalculationFormula::class);
    }

    /**
     * Get total quantity from all measurements.
     */
    public function getTotalQuantityFromMeasurements(): float
    {
        return $this->measurements()->sum('quantity');
    }

    /**
     * Get the rate model based on rate_type.
     * Helper method for backwards compatibility.
     */
    public function getRateModel()
    {
        return match($this->rate_type) {
            'dsr' => DsrRate::find($this->rate_id),
            'ssr' => SsrRate::find($this->rate_id),
            'wrd' => WrdRate::find($this->rate_id),
            default => null,
        };
    }

    /**
     * Calculate the rate including lead charges and local adjustments
     * Formula: Basic Rate + Lead Charges + Local Adjustments
     */
    public function calculateRateWithLeads(): float
    {
        $leadService = app(\App\Services\LeadService::class);
        return $leadService->calculateItemRate($this);
    }

    /**
     * Calculate the amount based on quantity and rate.
     * If measurements exist, use total from measurements.
     */
    public function calculateAmount(): void
    {
        // If measurements exist, use total from measurements
        if ($this->measurements()->count() > 0) {
            $this->quantity = $this->getTotalQuantityFromMeasurements();
        }
        
        $this->amount = $this->quantity * $this->rate;
        $this->save();

        // Update estimation totals
        $this->estimation->calculateTotals();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($item) {
            $item->estimation->calculateTotals();
        });

        static::deleted(function ($item) {
            $item->estimation->calculateTotals();
        });
    }
}
