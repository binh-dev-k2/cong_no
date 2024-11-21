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

    protected $guarded = ['id'];

    public const STATUS_HIDDEN = 0;
    public const STATUS_SHOW = 1;

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
        return $this->hasMany(CardHistory::class, 'card_id', 'id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'card_number', 'card_number');
    }

    public function debts()
    {
        return $this->hasMany(Debt::class, 'card_number', 'card_number');
    }
}
