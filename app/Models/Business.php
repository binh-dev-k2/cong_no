<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    use LogActivityTrait;
    protected $table = "businesses";

    protected $guarded = ['id'];

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

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id', 'id');
    }
}
