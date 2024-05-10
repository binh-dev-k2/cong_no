<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $table = "cards";

    protected $guarded = [];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_code', 'code');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function cardHistories(): HasMany
    {
        return $this->hasMany(CardHistory::class, 'id', 'card_id');
    }
}
