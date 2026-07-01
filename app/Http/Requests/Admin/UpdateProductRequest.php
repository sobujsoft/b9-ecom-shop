<?php

namespace App\Http\Requests\Admin;

use App\Concerns\ProductValidationRules;
use App\Models\Product;
use App\Support\RichTextSanitizer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductRequest extends FormRequest
{
    use ProductValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Product $product */
        $product = $this->route('product');

        return $this->productRules($product->id);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');

        $this->merge([
            'slug' => filled($slug) ? Str::slug((string) $slug) : Str::slug((string) $this->input('name')),
            'is_best_seller' => $this->boolean('is_best_seller'),
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
            'images' => $this->normalizeImages(),
            'description' => RichTextSanitizer::clean($this->input('description')),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function normalizeImages(): array
    {
        $images = $this->input('images', []);

        if (! is_array($images)) {
            return [];
        }

        return collect($images)
            ->filter(fn ($image): bool => is_array($image))
            ->map(function (array $image): array {
                $image['is_primary'] = filter_var($image['is_primary'] ?? false, FILTER_VALIDATE_BOOLEAN);

                return $image;
            })
            ->values()
            ->all();
    }
}
