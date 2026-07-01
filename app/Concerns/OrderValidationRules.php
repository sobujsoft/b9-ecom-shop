<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait OrderValidationRules
{
    /**
     * @return list<string>
     */
    public static function orderStatuses(): array
    {
        return ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    }

    /**
     * @return list<string>
     */
    public static function paymentStatuses(): array
    {
        return ['pending', 'paid', 'failed', 'cancelled'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function orderUpdateRules(): array
    {
        return [
            'status' => ['required', Rule::in(self::orderStatuses())],
            'payment_status' => ['required', Rule::in(self::paymentStatuses())],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
