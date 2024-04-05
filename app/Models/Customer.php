<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customer";

    protected $guarded = [];

    public function getCard(): HasMany
    {
        return $this->hasMany(Card::class, 'customer_id', 'id');
    }

    public function getDebt(): HasMany
    {
        return $this->hasMany(Debt::class, 'customer_id', 'id');
    }
}
