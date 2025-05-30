<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'name',
        'fee_percent'
    ];

    protected $casts = [
        'fee_percent' => 'float'
    ];

    /**
     * Get the agency businesses for the agency.
     */
    public function agencyBusinesses()
    {
        return $this->hasMany(AgencyBusiness::class);
    }

    /**
     * Get the agency machines for the agency.
     */
    public function agencyMachines()
    {
        return $this->hasMany(AgencyMachine::class);
    }

    /**
     * Get the machines through agency_machines.
     */
    public function machines()
    {
        return $this->belongsToMany(Machine::class, 'agency_machines');
    }
}
