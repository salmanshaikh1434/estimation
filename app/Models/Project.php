<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'client',
        'location',
        'description',
        'start_date',
        'end_date',
        'status',
        'face_sheet_template_id',
        'sanctioned_estimate_number',
        'financial_year',
        'prepared_by',
        'checked_by',
        'approved_by',
        'total_amount',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the face sheet template for the project.
     */
    public function faceSheetTemplate(): BelongsTo
    {
        return $this->belongsTo(FaceSheetTemplate::class);
    }

    /**
     * Get the estimations for the project.
     */
    public function estimations(): HasMany
    {
        return $this->hasMany(Estimation::class);
    }

    /**
     * Update the total amount based on estimations.
     */
    public function updateTotalAmount(): void
    {
        $this->total_amount = $this->estimations()->sum('total_amount');
        $this->save();
    }

    /**
     * Scope a query to only include projects of a given status.
     */
    public function scopeOfStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
