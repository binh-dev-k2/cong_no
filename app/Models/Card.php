<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;
    protected $table = "card";

    protected $guarded = [];

    public function Bank():BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'code');
    }

    public function Customer() :BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function getCardHistory()
    {
        return $this->hasMany(CardHistory::class, 'id', 'card_id');
    }
}
