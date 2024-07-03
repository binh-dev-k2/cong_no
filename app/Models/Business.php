<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $table = "businesses";

    protected $guarded = [];

    public function money()
    {
        return $this->hasMany(BusinessMoney::class, 'business_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_code', 'code');
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_number', 'card_number');
    }
}
