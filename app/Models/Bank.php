<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
     protected $table = "banks";

    protected $guarded = [];
    public function cards() {
        return $this->hasMany(Card::class, 'bank_id', 'code');
    }
}
