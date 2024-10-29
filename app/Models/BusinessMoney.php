<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessMoney extends Model
{
    use HasFactory;

    protected $table = "business_money";

    protected $guarded = ['id'];
}
