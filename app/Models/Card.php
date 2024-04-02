<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $table = "card";

    protected $guarded = [];

    public function Bank() {
        return $this->belongsTo(Bank::class, 'bank_id', 'code');
    }
}
