<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait CategoryValidationRules
{
    /**
     * @return array<string, mixed>
     */
    protected function categoryRules(?int $categoryId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:170',
                'alpha_dash',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'image_source' => ['required', Rule::in(['url', 'upload'])],
            'image' => [
                'nullable',
                'string',
                'max:2048',
                Rule::when($this->input('image_source') === 'url', ['url']),
            ],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
