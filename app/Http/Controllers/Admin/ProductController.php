<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductImageService $productImageService,
    ) {}

    /**
     * Display a listing of the products.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->with(['category:id,name', 'images' => fn ($query) => $query->where('is_primary', true)])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Product $product): array => $this->listPayload($product));

        return Inertia::render('admin/products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        return Inertia::render('admin/products/Create', [
            'categories' => $this->categoryOptions(),
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = $request->safe()->except(['images']);

            $product = Product::query()->create($data);

            $this->productImageService->syncImages(
                $product,
                $this->imagesFromRequest($request),
            );
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Product created.')]);

        return to_route('admin.products.index');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): Response
    {
        $product->load(['category:id,name', 'images' => fn ($query) => $query->orderBy('sort_order')]);

        return Inertia::render('admin/products/Show', [
            'product' => $this->detailPayload($product),
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): Response
    {
        $product->load(['category:id,name', 'images' => fn ($query) => $query->orderBy('sort_order')]);

        return Inertia::render('admin/products/Edit', [
            'product' => $this->detailPayload($product),
            'categories' => $this->categoryOptions(),
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product): void {
            $data = $request->safe()->except(['images']);

            $product->update($data);

            $this->productImageService->syncImages(
                $product,
                $this->imagesFromRequest($request),
            );
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Product updated.')]);

        return to_route('admin.products.index');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product): void {
            $this->productImageService->deleteUploadedImages($product);
            $product->delete();
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Product deleted.')]);

        return to_route('admin.products.index');
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    private function categoryOptions(): array
    {
        return Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function imagesFromRequest(StoreProductRequest|UpdateProductRequest $request): array
    {
        $images = $request->input('images', []);

        if (! is_array($images)) {
            return [];
        }

        foreach ($images as $index => $image) {
            if (! is_array($image)) {
                continue;
            }

            $uploadedFile = $request->file("images.{$index}.file");

            if ($uploadedFile instanceof UploadedFile) {
                $images[$index]['file'] = $uploadedFile;
            }
        }

        return array_values($images);
    }

    /**
     * @return array<string, mixed>
     */
    private function listPayload(Product $product): array
    {
        $primaryImage = $product->images->firstWhere('is_primary', true)
            ?? $product->images->first();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ],
            'price' => (float) $product->price,
            'compare_at_price' => $product->compare_at_price !== null
                ? (float) $product->compare_at_price
                : null,
            'stock_status' => $product->stock_status,
            'is_active' => $product->is_active,
            'is_best_seller' => $product->is_best_seller,
            'is_featured' => $product->is_featured,
            'sold_count' => $product->sold_count,
            'image' => $this->productImageService->resolveUrl($primaryImage?->image_path),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function detailPayload(Product $product): array
    {
        $primaryImage = $product->images->firstWhere('is_primary', true)
            ?? $product->images->first();

        return [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'price' => (float) $product->price,
            'compare_at_price' => $product->compare_at_price !== null
                ? (float) $product->compare_at_price
                : null,
            'stock_status' => $product->stock_status,
            'is_best_seller' => $product->is_best_seller,
            'is_featured' => $product->is_featured,
            'is_active' => $product->is_active,
            'sold_count' => $product->sold_count,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ],
            'image' => $this->productImageService->resolveUrl($primaryImage?->image_path),
            'images' => $product->images
                ->map(fn (ProductImage $image): array => $this->productImageService->formPayload($image))
                ->values()
                ->all(),
            'created_at' => $product->created_at?->toIso8601String() ?? '',
            'updated_at' => $product->updated_at?->toIso8601String() ?? '',
        ];
    }
}
