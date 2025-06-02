<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    use LogActivityTrait;

    protected $table = 'agencies';

    protected $fillable = [
        'name',
        'fee_percent',
        'machine_fee_percent',
        'owner_id'
    ];

    protected $casts = [
        'fee_percent' => 'float',
        'machine_fee_percent' => 'float'
    ];

    protected $with = ['users'];

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

    public function agencyUsers()
    {
        return $this->hasMany(AgencyUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'agency_users');
    }
}
