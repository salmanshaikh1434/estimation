<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceSheetTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization_name',
        'command_authority',
        'division_name',
        'sub_division_name',
        'executive_engineer',
        'fund_head',
        'major_head',
        'minor_head',
        'service_head',
        'departmental_head',
        'header_text',
        'footer_text',
        'logo_path',
        'is_default',
    ];

    protected $casts = [
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
