<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id',
    'gateway',
    'transaction_id',
    'val_id',
    'amount',
    'currency',
    'status',
    'card_type',
    'bank_tran_id',
    'store_amount',
    'raw_response',
    'paid_at',
])]
class Payment extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'store_amount' => 'decimal:2',
            'raw_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected $attributes = [
        'gateway' => 'sslcommerz',
        'currency' => 'BDT',
        'status' => 'initiated',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
