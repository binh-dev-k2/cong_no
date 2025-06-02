<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyUser extends Model
{
    protected $fillable = [
        'agency_id',
        'user_id'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}