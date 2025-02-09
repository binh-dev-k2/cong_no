<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessMoney extends Model
{
    use HasFactory;
    use LogActivityTrait;

    protected $table = "business_money";

    protected $guarded = ['id'];
}
