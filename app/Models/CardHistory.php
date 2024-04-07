<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardHistory extends Model
{
    use HasFactory;

    protected $table = "card_remind_history";

    public function setCustomer()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

}
