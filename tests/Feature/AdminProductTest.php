<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot access admin products', function () {
    $this->get(route('admin.products.index'))
        ->assertRedirect(route('login'));
});

test('customers cannot access admin products', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.products.index'))
        ->assertForbidden();
});

test('admins can view the products index', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/products/Index')
            ->has('products')
            ->has('products.0', fn (Assert $product) => $product
                ->has('id')
                ->has('name')
                ->has('slug')
                ->has('category')
                ->has('price')
                ->has('stock_status')
                ->has('image')
                ->etc()
            )
        );
});

test('admins can create a product with multiple images', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $payload = [
        'category_id' => $category->id,
        'name' => 'Desk Lamp',
        'slug' => 'desk-lamp',
        'short_description' => 'A modern desk lamp.',
        'description' => 'Bright adjustable desk lamp for your workspace.',
        'price' => 1999.50,
        'compare_at_price' => 2499.00,
        'stock_status' => 'in_stock',
        'is_best_seller' => false,
        'is_featured' => true,
        'is_active' => true,
        'sold_count' => 12,
        'images' => [
            [
                'source' => 'url',
                'image_path' => 'photo-1505740420928-5e560c06d30e',
                'alt_text' => 'Desk Lamp Front',
                'is_primary' => true,
                'sort_order' => 0,
            ],
            [
                'source' => 'url',
                'image_path' => 'photo-1523275335684-37898b6baf30',
                'alt_text' => 'Desk Lamp Side',
                'is_primary' => false,
                'sort_order' => 1,
            ],
        ],
    ];

    $this->actingAs($admin)
        ->post(route('admin.products.store'), $payload)
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->where('slug', 'desk-lamp')->firstOrFail();

    expect($product->category_id)->toBe($category->id)
        ->and($product->name)->toBe('Desk Lamp')
        ->and((float) $product->price)->toBe(1999.50)
        ->and($product->is_featured)->toBeTrue();

    $images = ProductImage::query()
        ->where('product_id', $product->id)
        ->orderBy('sort_order')
        ->get();

    expect($images)->toHaveCount(2)
        ->and($images->first()?->is_primary)->toBeTrue()
        ->and($images->first()?->sort_order)->toBe(0)
        ->and($images->last()?->sort_order)->toBe(1);
});

test('admins can create a product with uploaded images', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();
    $firstFile = UploadedFile::fake()->image('product-1.jpg');
    $secondFile = UploadedFile::fake()->image('product-2.jpg');

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Uploaded Product',
            'slug' => 'uploaded-product',
            'short_description' => 'Uploaded image product.',
            'description' => 'Product with uploaded image.',
            'price' => 1500,
            'stock_status' => 'in_stock',
            'is_best_seller' => false,
            'is_featured' => false,
            'is_active' => true,
            'sold_count' => 0,
            'images' => [
                [
                    'source' => 'upload',
                    'alt_text' => 'Primary upload',
                    'is_primary' => true,
                    'sort_order' => 0,
                    'file' => $firstFile,
                ],
                [
                    'source' => 'upload',
                    'alt_text' => 'Secondary upload',
                    'is_primary' => false,
                    'sort_order' => 1,
                    'file' => $secondFile,
                ],
            ],
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->where('slug', 'uploaded-product')->firstOrFail();
    $images = ProductImage::query()->where('product_id', $product->id)->orderBy('sort_order')->get();

    expect($images)->toHaveCount(2)
        ->and($images->first()?->image_path)->toStartWith('products/')
        ->and($images->last()?->image_path)->toStartWith('products/');

    Storage::disk('public')->assertExists($images->first()->image_path);
    Storage::disk('public')->assertExists($images->last()->image_path);
});

test('admins can view a product', function () {
    $admin = User::factory()->admin()->create();
    $product = Product::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.products.show', $product))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/products/Show')
            ->where('product.id', $product->id)
            ->where('product.name', $product->name)
            ->has('product.images', 1)
        );
});

test('admins can update a product and reorder images', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->for($category)->create([
        'name' => 'Old Product',
        'slug' => 'old-product',
    ]);

    $firstImage = ProductImage::query()->where('product_id', $product->id)->firstOrFail();
    $secondImage = ProductImage::query()->create([
        'product_id' => $product->id,
        'image_path' => 'photo-1523275335684-37898b6baf30',
        'alt_text' => 'Secondary',
        'is_primary' => false,
        'sort_order' => 1,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Updated Product',
            'slug' => 'updated-product',
            'short_description' => 'Updated summary',
            'description' => 'Updated description',
            'price' => 3200,
            'compare_at_price' => null,
            'stock_status' => 'stock_out',
            'is_best_seller' => true,
            'is_featured' => false,
            'is_active' => false,
            'sold_count' => 25,
            'images' => [
                [
                    'id' => $secondImage->id,
                    'source' => 'url',
                    'image_path' => 'photo-1523275335684-37898b6baf30',
                    'alt_text' => 'Now primary',
                    'is_primary' => true,
                    'sort_order' => 0,
                ],
                [
                    'id' => $firstImage->id,
                    'source' => 'url',
                    'image_path' => $firstImage->image_path,
                    'alt_text' => 'Now secondary',
                    'is_primary' => false,
                    'sort_order' => 1,
                ],
            ],
        ])
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();

    expect($product->name)->toBe('Updated Product')
        ->and($product->slug)->toBe('updated-product')
        ->and($product->stock_status)->toBe('stock_out')
        ->and($product->is_best_seller)->toBeTrue()
        ->and($product->is_active)->toBeFalse();

    $primaryImage = ProductImage::query()
        ->where('product_id', $product->id)
        ->where('is_primary', true)
        ->first();

    expect($primaryImage?->id)->toBe($secondImage->id)
        ->and($primaryImage?->sort_order)->toBe(0);
});

test('admins can delete a product and its uploaded images', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $path = UploadedFile::fake()->image('product.jpg')->store('products', 'public');
    $product = Product::factory()->create();

    ProductImage::query()->where('product_id', $product->id)->delete();
    ProductImage::query()->create([
        'product_id' => $product->id,
        'image_path' => $path,
        'alt_text' => $product->name,
        'is_primary' => true,
        'sort_order' => 0,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertSoftDeleted('products', [
        'id' => $product->id,
    ]);

    Storage::disk('public')->assertMissing($path);
});

test('product slug is generated from name when omitted', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Garden Hose',
            'slug' => '',
            'price' => 999,
            'stock_status' => 'in_stock',
            'sold_count' => 0,
            'is_active' => true,
            'images' => [],
        ])
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Garden Hose',
        'slug' => 'garden-hose',
    ]);
});

test('product slug must be unique', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();
    Product::factory()->for($category)->create(['slug' => 'duplicate-slug']);

    $this->actingAs($admin)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Duplicate',
            'slug' => 'duplicate-slug',
            'price' => 1000,
            'stock_status' => 'in_stock',
            'sold_count' => 0,
            'is_active' => true,
            'images' => [],
        ])
        ->assertRedirect(route('admin.products.create'))
        ->assertSessionHasErrors('slug');
});
