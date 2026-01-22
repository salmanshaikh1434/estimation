<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalculationFormula extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'calculation_type',
        'description',
        'formula',
        'parameters',
        'validation_rules',
        'unit',
        'example',
        'is_active',
        'usage_count',
    ];

    protected $casts = [
        'parameters' => 'array',
        'validation_rules' => 'array',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Get estimation items using this formula
     */
    public function estimationItems(): HasMany
    {
        return $this->hasMany(EstimationItem::class);
    }

    /**
     * Scope to get active formulas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get formulas by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get formulas by calculation type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('calculation_type', $type);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get parameter labels
     */
    public function getParameterLabels(): array
    {
        return collect($this->parameters)->pluck('label', 'name')->toArray();
    }

    /**
     * Validate parameters against rules
     */
    public function validateParameters(array $params): bool
    {
        if (!$this->validation_rules) {
            return true;
        }

        $validator = validator($params, $this->validation_rules);
        return !$validator->fails();
    }
}
