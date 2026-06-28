<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    private const PER_PAGE = 8;

    private const VALID_SORTS = ['newest', 'price-asc', 'price-desc', 'best'];

    private const VALID_PRICE_RANGES = ['all', '0-1000', '1000-3000', '3000-6000', '6000-'];

    /**
     * Display the storefront product catalog.
     */
    public function __invoke(Request $request): Response
    {
        $filters = $this->resolvedFilters($request);
        $paginator = $this->catalogQuery($filters)
            ->paginate(self::PER_PAGE, ['*'], 'page', $filters['page']);

        return Inertia::render('shop/Shop', [
            'categories' => fn () => $this->categoryFilters(),
            'products' => $paginator->getCollection()
                ->map(fn (Product $product) => $this->mapCatalogProduct($product))
                ->all(),
            'meta' => [
                'total' => $paginator->total(),
                'perPage' => $paginator->perPage(),
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
            ],
            'filters' => $filters,
        ]);
    }

    /**
     * @return array{
     *     categories: list<string>,
     *     price: string,
     *     inStock: bool,
     *     sort: string,
     *     search: string,
     *     page: int
     * }
     */
    private function resolvedFilters(Request $request): array
    {
        $categories = array_values(array_filter(
            array_map('strval', (array) $request->input('categories', [])),
        ));

        $price = (string) $request->input('price', 'all');
        $sort = (string) $request->input('sort', 'newest');

        return [
            'categories' => $categories,
            'price' => in_array($price, self::VALID_PRICE_RANGES, true) ? $price : 'all',
            'inStock' => $request->boolean('in_stock'),
            'sort' => in_array($sort, self::VALID_SORTS, true) ? $sort : 'newest',
            'search' => (string) $request->input('search', ''),
            'page' => max(1, (int) $request->input('page', 1)),
        ];
    }

    /**
     * @param  array{categories: list<string>, price: string, inStock: bool, sort: string, search: string, page: int}  $filters
     */
    private function catalogQuery(array $filters): Builder
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with([
                'category',
                'images' => fn ($q) => $q->where('is_primary', true),
            ])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_approved', true)], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->where('is_approved', true)]);

        if (! empty($filters['categories'])) {
            $slugs = $filters['categories'];
            $query->whereHas('category', fn (Builder $q) => $q->whereIn('slug', $slugs));
        }

        [$min, $max] = $this->parsePriceRange($filters['price']);

        if ($min !== null) {
            $query->where('price', '>=', $min);
        }

        if ($max !== null) {
            $query->where('price', '<=', $max);
        }

        if ($filters['inStock']) {
            $query->where('stock_status', 'in_stock');
        }

        if ($filters['search'] !== '') {
            $term = $filters['search'];
            $query->where(fn (Builder $q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%")
            );
        }

        match ($filters['sort']) {
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'best' => $query->orderByDesc('sold_count'),
            default => $query->orderByDesc('id'),
        };

        return $query;
    }

    /**
     * @return array{0: float|null, 1: float|null}
     */
    private function parsePriceRange(string $price): array
    {
        if ($price === 'all') {
            return [null, null];
        }

        [$min, $max] = explode('-', $price, 2);

        return [
            $min !== '' ? (float) $min : null,
            $max !== '' ? (float) $max : null,
        ];
    }

    /**
     * @return list<array{name: string, slug: string}>
     */
    private function categoryFilters(): array
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['name', 'slug'])
            ->map(fn (Category $category): array => [
                'name' => $category->name,
                'slug' => $category->slug,
            ])
            ->all();
    }

    /**
     * @return array{
     *     id: int, name: string, slug: string, price: float, oldPrice: float|null,
     *     img: string, rating: float, reviews: int, inStock: bool,
     *     category: string, tag: string|null, sold: int
     * }
     */
    private function mapCatalogProduct(Product $product): array
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
            'category' => $product->category->name,
            'tag' => $product->is_best_seller ? 'Best Seller' : ($product->is_featured ? 'New' : null),
            'sold' => $product->sold_count,
        ];
    }
}
