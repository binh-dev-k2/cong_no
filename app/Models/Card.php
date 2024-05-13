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

    public const TYPE_BUSINESS = 1;
    public const TYPE_DEBT = 2;

    public const STATUS_UNPAID = 1;
    public const STATUS_PAID = 2;

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

    public function money(): HasMany
    {
        return $this->hasMany(CardMoney::class, 'card_id', 'id');
    }
}
