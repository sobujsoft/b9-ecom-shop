<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait ProductValidationRules
{
    /**
     * @return array<string, mixed>
     */
    protected function productRules(?int $productId = null): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:220',
                'alpha_dash',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock_status' => ['required', Rule::in(['in_stock', 'stock_out'])],
            'is_best_seller' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sold_count' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*.id' => ['nullable', 'integer'],
            'images.*.source' => ['required', Rule::in(['url', 'upload'])],
            'images.*.image_path' => ['nullable', 'string', 'max:2048'],
            'images.*.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'images.*.is_primary' => ['boolean'],
            'images.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
