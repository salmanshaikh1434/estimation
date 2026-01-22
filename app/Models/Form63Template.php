<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form63Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'form_number',
        'description',
        'fields',
        'template_content',
        'version',
        'is_default',
    ];

    protected $casts = [
        'fields' => 'array',
        'is_default' => 'boolean',
    ];

    /**
     * Get the default template
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first() 
            ?? static::first();
    }

    /**
     * Set this template as default
     */
    public function setAsDefault(): void
    {
        // Unset all other defaults
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }
}
