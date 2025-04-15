<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    use LogActivityTrait;

    protected $table = "banks";
    
    public $timestamps = false;

    protected $guarded = ['id'];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'bank_code', 'code');
    }
}
