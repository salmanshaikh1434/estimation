<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'description',
        'rate_type',
        'royalty_amount',
        'contingency_percentage',
        'gst_percentage',
        'sub_total',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'royalty_amount' => 'decimal:2',
        'contingency_percentage' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the project that owns the estimation.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that owns the estimation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the estimation.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EstimationItem::class)->orderBy('sort_order');
    }

    /**
     * Get the leads for this estimation.
     */
    public function leads()
    {
        return $this->hasMany(EstimationLead::class);
    }

    /**
     * Calculate and update the estimation totals.
     */
    public function calculateTotals(): void
    {
        // Calculate sub-total from all items
        $this->sub_total = $this->items()->sum('amount');
        
        // Add royalty
        $afterRoyalty = $this->sub_total + $this->royalty_amount;
        
        // Add contingency
        $contingencyAmount = ($afterRoyalty * $this->contingency_percentage) / 100;
        $afterContingency = $afterRoyalty + $contingencyAmount;
        
        // Add GST
        $gstAmount = ($afterContingency * $this->gst_percentage) / 100;
        $this->total_amount = $afterContingency + $gstAmount;
        
        $this->save();

        // Update project total
        $this->project->updateTotalAmount();
    }

    /**
     * Scope a query to only include estimations of a given status.
     */
    public function scopeOfStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
