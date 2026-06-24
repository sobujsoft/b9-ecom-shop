<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'image_path',
    'alt_text',
    'is_primary',
    'sort_order',
])]
class ProductImage extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected $attributes = [
        'is_primary' => false,
        'sort_order' => 0,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
