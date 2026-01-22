<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'base_rate',
        'description',
        'is_active',
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the consumption records for this material
     */
    public function consumptions(): HasMany
    {
        return $this->hasMany(ItemMaterialConsumption::class);
    }

    /**
     * Get the estimation leads for this material
     */
    public function estimationLeads(): HasMany
    {
        return $this->hasMany(EstimationLead::class);
    }
}
