<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageService
{
    /**
     * Resolve a stored image value to a public URL.
     */
    public function resolveUrl(?string $image, int $width = 400, int $quality = 70): ?string
    {
        if (! filled($image)) {
            return null;
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        if (Str::startsWith($image, 'photo-')) {
            return "https://images.unsplash.com/{$image}?auto=format&fit=crop&w={$width}&q={$quality}";
        }

        return Storage::disk('public')->url($image);
    }

    /**
     * Determine whether the image value refers to an uploaded file.
     */
    public function isUploadedPath(?string $image): bool
    {
        return filled($image)
            && ! filter_var($image, FILTER_VALIDATE_URL)
            && ! Str::startsWith($image, 'photo-');
    }

    /**
     * @return array{
     *     id: int,
     *     source: 'url'|'upload',
     *     image_path: string,
     *     preview: string|null,
     *     alt_text: string|null,
     *     is_primary: bool,
     *     sort_order: int
     * }
     */
    public function formPayload(ProductImage $image): array
    {
        $isUploaded = $this->isUploadedPath($image->image_path);

        return [
            'id' => $image->id,
            'source' => $isUploaded ? 'upload' : 'url',
            'image_path' => $image->image_path,
            'preview' => $this->resolveUrl($image->image_path),
            'alt_text' => $image->alt_text,
            'is_primary' => $image->is_primary,
            'sort_order' => $image->sort_order,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $images
     */
    public function syncImages(Product $product, array $images): void
    {
        $normalizedImages = $this->normalizeImages($images);
        $existingImages = $product->images()->get()->keyBy('id');
        $keptIds = [];

        foreach ($normalizedImages as $imageData) {
            $imageId = filled($imageData['id'] ?? null) ? (int) $imageData['id'] : null;
            $existingImage = $imageId !== null ? $existingImages->get($imageId) : null;

            if ($imageId !== null && $existingImage === null) {
                continue;
            }

            $storedPath = $this->resolveStoredPathFromInput($imageData, $existingImage);

            if ($storedPath === null) {
                if ($existingImage !== null) {
                    $this->deleteIfUploaded($existingImage->image_path);
                    $existingImage->delete();
                }

                continue;
            }

            $attributes = [
                'image_path' => $storedPath,
                'alt_text' => filled($imageData['alt_text'] ?? null)
                    ? $imageData['alt_text']
                    : $product->name,
                'is_primary' => (bool) ($imageData['is_primary'] ?? false),
                'sort_order' => (int) $imageData['sort_order'],
            ];

            if ($existingImage !== null) {
                $existingImage->update($attributes);
                $keptIds[] = $existingImage->id;

                continue;
            }

            $createdImage = $product->images()->create($attributes);
            $keptIds[] = $createdImage->id;
        }

        $product->images()
            ->whereNotIn('id', $keptIds)
            ->get()
            ->each(function (ProductImage $image): void {
                $this->deleteIfUploaded($image->image_path);
                $image->delete();
            });
    }

    /**
     * Delete uploaded images for a product.
     */
    public function deleteUploadedImages(Product $product): void
    {
        $product->loadMissing('images');

        foreach ($product->images as $image) {
            $this->deleteIfUploaded($image->image_path);
        }
    }

    /**
     * Delete an uploaded product image from storage when no longer needed.
     */
    public function deleteIfUploaded(?string $image): void
    {
        if (! $this->isUploadedPath($image)) {
            return;
        }

        Storage::disk('public')->delete($image);
    }

    /**
     * @param  list<array<string, mixed>>  $images
     * @return list<array<string, mixed>>
     */
    private function normalizeImages(array $images): array
    {
        /** @var Collection<int, array<string, mixed>> $collection */
        $collection = collect($images)
            ->sortBy(fn (array $image): int => (int) ($image['sort_order'] ?? 0))
            ->values()
            ->map(function (array $image, int $index): array {
                $image['sort_order'] = $index;

                return $image;
            });

        $primaryIndex = $collection->search(
            fn (array $image): bool => filter_var($image['is_primary'] ?? false, FILTER_VALIDATE_BOOLEAN),
        );

        if ($primaryIndex === false && $collection->isNotEmpty()) {
            $primaryIndex = 0;
        }

        return $collection
            ->map(function (array $image, int $index) use ($primaryIndex): array {
                $image['is_primary'] = $index === $primaryIndex;

                return $image;
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $imageData
     */
    private function resolveStoredPathFromInput(array $imageData, ?ProductImage $existingImage = null): ?string
    {
        $source = (string) ($imageData['source'] ?? 'url');

        if ($source === 'upload') {
            $uploadedFile = $imageData['file'] ?? null;

            if ($uploadedFile instanceof UploadedFile) {
                $this->deleteIfUploaded($existingImage?->image_path);

                return $uploadedFile->store('products', 'public');
            }

            return $existingImage?->image_path;
        }

        if ($existingImage !== null && $this->isUploadedPath($existingImage->image_path)) {
            $this->deleteIfUploaded($existingImage->image_path);
        }

        $imagePath = $imageData['image_path'] ?? null;

        return filled($imagePath) ? $imagePath : null;
    }
}
