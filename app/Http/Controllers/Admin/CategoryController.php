<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryImageService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryImageService $categoryImageService,
    ) {}

    /**
     * Display a listing of the categories.
     */
    public function index(): Response
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): array => $this->categoryPayload($category));

        return Inertia::render('admin/categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): Response
    {
        return Inertia::render('admin/categories/Create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $this->categoryImageService->prepareForStorage(
            $request->safe()->except(['image_file']),
            $request->file('image_file'),
        );

        Category::query()->create($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category created.')]);

        return to_route('admin.categories.index');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): Response
    {
        $category->loadCount('products');

        return Inertia::render('admin/categories/Show', [
            'category' => $this->categoryPayload($category),
        ]);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): Response
    {
        return Inertia::render('admin/categories/Edit', [
            'category' => $this->categoryPayload($category),
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $this->categoryImageService->prepareForStorage(
            $request->safe()->except(['image_file']),
            $request->file('image_file'),
            $category,
        );

        $category->update($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category updated.')]);

        return to_route('admin.categories.index');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryImageService->deleteIfUploaded($category->image);

        $category->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category deleted.')]);

        return to_route('admin.categories.index');
    }

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     image: string|null,
     *     image_source: 'url'|'upload',
     *     image_url: string,
     *     description: string|null,
     *     sort_order: int,
     *     is_active: bool,
     *     products_count: int,
     *     created_at: string,
     *     updated_at: string
     * }
     */
    private function categoryPayload(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            ...$this->categoryImageService->payloadForCategory($category),
            'description' => $category->description,
            'sort_order' => $category->sort_order,
            'is_active' => $category->is_active,
            'products_count' => (int) ($category->products_count ?? 0),
            'created_at' => $category->created_at?->toIso8601String() ?? '',
            'updated_at' => $category->updated_at?->toIso8601String() ?? '',
        ];
    }
}
