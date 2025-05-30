<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyMachine extends Model
{
    use HasFactory;

    protected $table = 'agency_machines';

    protected $fillable = [
        'agency_id',
        'machine_id'
    ];

    /**
     * Get the agency that owns the machine relationship.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the machine that owns the relationship.
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
