<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyBusiness extends Model
{
    use HasFactory;

    use LogActivityTrait;

    protected $table = 'agency_businesses';

    protected $fillable = [
        'agency_id',
        'machine_id',
        'total_money',
        'profit',
        'image_front',
        'image_summary',
        'standard_code',
        'is_completed',
        'amount_to_pay'
    ];

    protected $casts = [
        'total_money' => 'integer',
        'amount_to_pay' => 'decimal:2',
        'profit' => 'decimal:2',
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
