<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    use HasFactory;
    use LogActivityTrait;
    
    protected $table = "debts";

    protected $guarded = ['id'];

    public const STATUS_UNPAID = 0;
    public const STATUS_PAID = 1;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_number', 'card_number');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'formality', 'formality');
    }
}
