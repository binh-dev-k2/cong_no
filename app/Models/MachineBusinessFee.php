<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineBusinessFee extends Model
{
    use HasFactory;

    protected $table = 'machine_business_fees';

    protected $guarded = ['id'];
}
