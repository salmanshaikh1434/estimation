<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimationLead extends Model
{
    protected $fillable = [
        'estimation_id',
        'material_id',
        'quarry_location',
        'lead_distance_km',
        'lead_rate_per_km',
        'total_lead_charge',
    ];

    protected $casts = [
        'lead_distance_km' => 'decimal:2',
        'lead_rate_per_km' => 'decimal:2',
        'total_lead_charge' => 'decimal:2',
    ];

    /**
     * Get the estimation that owns this lead
     */
    public function estimation(): BelongsTo
    {
        return $this->belongsTo(Estimation::class);
    }

    /**
     * Get the material for this lead
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Calculate the total lead charge
     * Formula: distance × rate per km
     */
    public function calculateLeadCharge(): float
    {
        $this->total_lead_charge = $this->lead_distance_km * $this->lead_rate_per_km;
        return (float) $this->total_lead_charge;
    }

    /**
     * Boot method to auto-calculate lead charge
     */
    protected static function booted()
    {
        static::saving(function ($lead) {
            if ($lead->isDirty(['lead_distance_km', 'lead_rate_per_km'])) {
                $lead->calculateLeadCharge();
            }
        });
    }
}
