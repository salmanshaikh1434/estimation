<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemMaterialConsumption extends Model
{
    protected $table = 'item_material_consumption';

    protected $fillable = [
        'ssr_rate_id',
        'material_id',
        'consumption_factor',
    ];

    protected $casts = [
        'consumption_factor' => 'decimal:4',
    ];

    /**
     * Get the SSR rate that owns this consumption record
     */
    public function ssrRate(): BelongsTo
    {
        return $this->belongsTo(SsrRate::class);
    }

    /**
     * Get the material for this consumption record
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
