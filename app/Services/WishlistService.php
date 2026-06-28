<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Wishlist;

class WishlistService
{
    /**
     * Total saved items for the authenticated user.
     */
    public function count(): int
    {
        if (! auth()->id()) {
            return 0;
        }

        return Wishlist::where('user_id', auth()->id())->count();
    }

    /**
     * Product IDs in the authenticated user's wishlist.
     *
     * @return list<int>
     */
    public function productIds(): array
    {
        if (! auth()->id()) {
            return [];
        }

        return Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Full wishlist items resolved with product details.
     *
     * @return list<array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     price: float,
     *     oldPrice: float|null,
     *     img: string,
     *     rating: float,
     *     reviews: int,
     *     inStock: bool
     * }>
     */
    public function items(): array
    {
        if (! auth()->id()) {
            return [];
        }

        $productIds = Wishlist::where('user_id', auth()->id())
            ->latest()
            ->pluck('product_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        if ($productIds === []) {
            return [];
        }

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->with(['images' => fn ($query) => $query->where('is_primary', true)])
            ->withAvg(['reviews' => fn ($query) => $query->where('is_approved', true)], 'rating')
            ->withCount(['reviews' => fn ($query) => $query->where('is_approved', true)])
            ->get()
            ->keyBy('id');

        return collect($productIds)
            ->map(fn (int $productId): ?array => ($product = $products->get($productId))
                ? $this->mapProduct($product)
                : null)
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Add a product to the wishlist (no-op if already saved).
     */
    public function add(int $productId): void
    {
        $userId = auth()->id();

        if (! $userId) {
            return;
        }

        Wishlist::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove(int $productId): void
    {
        if (! auth()->id()) {
            return;
        }

        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * Remove all items from the wishlist.
     */
    public function clear(): void
    {
        if (! auth()->id()) {
            return;
        }

        Wishlist::where('user_id', auth()->id())->delete();
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     price: float,
     *     oldPrice: float|null,
     *     img: string,
     *     rating: float,
     *     reviews: int,
     *     inStock: bool
     * }
     */
    private function mapProduct(Product $product): array
    {
        $primaryImage = $product->images->first();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'oldPrice' => $product->compare_at_price !== null
                ? (float) $product->compare_at_price
                : null,
            'img' => $primaryImage?->image_path ?? '',
            'rating' => round((float) ($product->reviews_avg_rating ?? 0), 1),
            'reviews' => (int) $product->reviews_count,
            'inStock' => $product->stock_status === 'in_stock',
        ];
    }
}
