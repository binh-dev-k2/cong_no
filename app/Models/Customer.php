<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customers";

    protected $guarded = ['id'];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'customer_id', 'id');
    }

    public function getDebt(): HasMany
    {
        return $this->hasMany(Debt::class, 'customer_id', 'id');
    }

}
