<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $table = 'business_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public const TYPE = [
        'MONEY', 'PERCENT'
    ];
}
