<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Services\CategoryImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    private const HOMEPAGE_PRODUCT_LIMIT = 4;

    public function __construct(
        private readonly CategoryImageService $categoryImageService,
    ) {}

    /**
     * Display the storefront homepage.
     */
    public function __invoke(): Response
    {
        return Inertia::render('shop/Home', [
            'heroSlides' => $this->heroSlides(),
            'categories' => $this->categories(),
            'bestSellingProducts' => $this->products(
                fn ($query) => $query->where('is_best_seller', true)->orderByDesc('sold_count'),
                'Best Seller',
            ),
            'newCollectionProducts' => $this->products(
                fn ($query) => $query->where('is_featured', true)->orderByDesc('created_at'),
                'New',
            ),
        ]);
    }

    /**
     * @return list<array{src: string, alt: string}>
     */
    private function heroSlides(): array
    {
        return HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['image'])
            ->map(fn (HeroSlide $slide): array => [
                'src' => $slide->image,
                'alt' => 'Featured promotion banner',
            ])
            ->all();
    }

    /**
     * @return list<array{name: string, img: string, href: string}>
     */
    private function categories(): array
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['name', 'slug', 'image'])
            ->map(fn (Category $category): array => [
                'name' => $category->name,
                'img' => $this->categoryImageService->resolveUrl($category->image) ?? '',
                'href' => route('shop.index', ['category' => $category->name]),
            ])
            ->all();
    }

    /**
     * @param  callable(Builder<Product>): mixed  $scope
     * @return list<array{
     *     name: string,
     *     slug: string,
     *     price: float,
     *     oldPrice: float|null,
     *     img: string,
     *     rating: float,
     *     reviews: int,
     *     inStock: bool,
     *     tag: string
     * }>
     */
    private function products(callable $scope, string $tag): array
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['images' => fn ($query) => $query->where('is_primary', true)])
            ->withAvg(['reviews' => fn ($query) => $query->where('is_approved', true)], 'rating')
            ->withCount(['reviews' => fn ($query) => $query->where('is_approved', true)]);

        $scope($query);

        /** @var Collection<int, Product> $products */
        $products = $query
            ->limit(self::HOMEPAGE_PRODUCT_LIMIT)
            ->get();

        return $this->mapProducts($products, $tag)->all();
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return SupportCollection<int, array{
     *     name: string,
     *     slug: string,
     *     price: float,
     *     oldPrice: float|null,
     *     img: string,
     *     rating: float,
     *     reviews: int,
     *     inStock: bool,
     *     tag: string
     * }>
     */
    private function mapProducts(Collection $products, string $tag): SupportCollection
    {
        return $products->map(function (Product $product) use ($tag): array {
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
                'tag' => $tag,
            ];
        });
    }
}
