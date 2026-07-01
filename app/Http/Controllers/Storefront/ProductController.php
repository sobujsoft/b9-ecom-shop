<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Support\RichTextSanitizer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    private const RELATED_PRODUCT_LIMIT = 4;

    /**
     * Display the product details page.
     */
    public function show(string $slug): Response|HttpResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'category',
                'images' => fn ($query) => $query->orderBy('sort_order'),
                'reviews' => fn ($query) => $query
                    ->where('is_approved', true)
                    ->with('user')
                    ->latest('approved_at'),
            ])
            ->withAvg(['reviews' => fn ($query) => $query->where('is_approved', true)], 'rating')
            ->withCount(['reviews' => fn ($query) => $query->where('is_approved', true)])
            ->first();

        abort_if($product === null, 404);

        return Inertia::render('shop/ProductShow', [
            'product' => $this->mapProductDetail($product),
            'relatedProducts' => $this->relatedProducts($product),
        ]);
    }

    /**
     * @return array{
     *     slug: string,
     *     name: string,
     *     category: string,
     *     categoryHref: string,
     *     price: float,
     *     oldPrice: float|null,
     *     img: string,
     *     rating: float,
     *     reviews: int,
     *     inStock: bool,
     *     tag: string|null,
     *     summary: string,
     *     description: string,
     *     features: list<string>,
     *     images: list<array{full: string, thumb: string}>,
     *     ratingBreakdown: list<array{stars: int, percent: int}>,
     *     reviewList: list<array{name: string, rating: int, date: string, verified: bool, text: string}>
     * }
     */
    private function mapProductDetail(Product $product): array
    {
        $primaryImage = $product->images->firstWhere('is_primary', true)
            ?? $product->images->first();

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'category' => $product->category->name,
            'categoryHref' => route('shop.index', ['category' => $product->category->name]),
            'price' => (float) $product->price,
            'oldPrice' => $product->compare_at_price !== null
                ? (float) $product->compare_at_price
                : null,
            'img' => $primaryImage?->image_path ?? '',
            'rating' => round((float) ($product->reviews_avg_rating ?? 0), 1),
            'reviews' => (int) $product->reviews_count,
            'inStock' => $product->stock_status === 'in_stock',
            'tag' => $this->productTag($product),
            'summary' => $product->short_description ?? '',
            'description' => RichTextSanitizer::forDisplay($product->description),
            'features' => [],
            'images' => $this->mapImages($product->images),
            'ratingBreakdown' => $this->ratingBreakdown($product->reviews),
            'reviewList' => $this->mapReviews($product->reviews),
        ];
    }

    /**
     * @return list<array{
     *     name: string,
     *     slug: string,
     *     price: float,
     *     oldPrice: float|null,
     *     img: string,
     *     rating: float,
     *     reviews: int,
     *     inStock: bool,
     *     tag: string|null
     * }>
     */
    private function relatedProducts(Product $product): array
    {
        return Product::query()
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->with(['images' => fn ($query) => $query->where('is_primary', true)])
            ->withAvg(['reviews' => fn ($query) => $query->where('is_approved', true)], 'rating')
            ->withCount(['reviews' => fn ($query) => $query->where('is_approved', true)])
            ->inRandomOrder()
            ->limit(self::RELATED_PRODUCT_LIMIT)
            ->get()
            ->map(function (Product $related): array {
                $primaryImage = $related->images->first();

                return [
                    'id' => $related->id,
                    'name' => $related->name,
                    'slug' => $related->slug,
                    'price' => (float) $related->price,
                    'oldPrice' => $related->compare_at_price !== null
                        ? (float) $related->compare_at_price
                        : null,
                    'img' => $primaryImage?->image_path ?? '',
                    'rating' => round((float) ($related->reviews_avg_rating ?? 0), 1),
                    'reviews' => (int) $related->reviews_count,
                    'inStock' => $related->stock_status === 'in_stock',
                    'tag' => $this->productTag($related),
                ];
            })
            ->all();
    }

    private function productTag(Product $product): ?string
    {
        if ($product->is_best_seller) {
            return 'Best Seller';
        }

        if ($product->is_featured) {
            return 'New';
        }

        return null;
    }

    /**
     * @param  Collection<int, ProductImage>  $images
     * @return list<array{full: string, thumb: string}>
     */
    private function mapImages($images): array
    {
        if ($images->isEmpty()) {
            return [];
        }

        return $images
            ->map(fn (ProductImage $image): array => [
                'full' => $this->imageUrl($image->image_path, 900, 75),
                'thumb' => $this->imageUrl($image->image_path, 200, 70),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @return list<array{stars: int, percent: int}>
     */
    private function ratingBreakdown($reviews): array
    {
        $total = $reviews->count();

        return collect(range(5, 1))
            ->map(function (int $stars) use ($reviews, $total): array {
                $count = $reviews->where('rating', $stars)->count();

                return [
                    'stars' => $stars,
                    'percent' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
                ];
            })
            ->all();
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @return list<array{name: string, rating: int, date: string, verified: bool, text: string}>
     */
    private function mapReviews($reviews): array
    {
        return $reviews
            ->map(fn (Review $review): array => [
                'name' => $review->user->name,
                'rating' => $review->rating,
                'date' => $review->approved_at?->format('j M Y') ?? $review->created_at->format('j M Y'),
                'verified' => $review->order_id !== null,
                'text' => $review->comment ?? '',
            ])
            ->all();
    }

    private function imageUrl(string $path, int $width, int $quality = 75): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return "https://images.unsplash.com/{$path}?auto=format&fit=crop&w={$width}&q={$quality}";
    }
}
