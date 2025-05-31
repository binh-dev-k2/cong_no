<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyBusiness extends Model
{
    use HasFactory;

    protected $table = 'agency_businessess';

    protected $fillable = [
        'agency_id',
        'machine_id',
        'total_money',
        'profit',
        'image_front',
        'image_summary',
        'standard_code',
        'is_completed'
    ];

    protected $casts = [
        'total_money' => 'integer',
        'is_completed' => 'boolean'
    ];

    /**
     * Get the agency that owns the business.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the machine that owns the business.
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
