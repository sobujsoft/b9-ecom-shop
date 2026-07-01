<?php

use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot access admin categories', function () {
    $this->get(route('admin.categories.index'))
        ->assertRedirect(route('login'));
});

test('customers cannot access admin categories', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.categories.index'))
        ->assertForbidden();
});

test('admins can view the categories index', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::query()->where('role', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/categories/Index')
            ->has('categories', 6)
            ->has('categories.0', fn (Assert $category) => $category
                ->has('id')
                ->has('name')
                ->has('slug')
                ->has('image')
                ->has('image_source')
                ->has('image_url')
                ->has('description')
                ->has('sort_order')
                ->has('is_active')
                ->has('products_count')
                ->etc()
            )
        );
});

test('admins can create a category with an image url', function () {
    $admin = User::factory()->admin()->create();

    $payload = [
        'name' => 'Toys',
        'slug' => 'toys',
        'image_source' => 'url',
        'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70',
        'description' => 'Fun toys for all ages.',
        'sort_order' => 7,
        'is_active' => true,
    ];

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), $payload)
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Toys',
        'slug' => 'toys',
        'image' => $payload['image'],
        'sort_order' => 7,
        'is_active' => true,
    ]);
});

test('admins can create a category with an uploaded image', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('category.jpg');

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Uploaded Category',
            'slug' => 'uploaded-category',
            'image_source' => 'upload',
            'image_file' => $file,
            'sort_order' => 3,
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.categories.index'));

    $category = Category::query()->where('slug', 'uploaded-category')->firstOrFail();

    expect($category->image)->toStartWith('categories/');
    Storage::disk('public')->assertExists($category->image);
});

test('admins can view a category', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.categories.show', $category))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/categories/Show')
            ->where('category.id', $category->id)
            ->where('category.name', $category->name)
            ->where('category.image_source', 'url')
        );
});

test('admins can update a category', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'slug' => 'old-name',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.categories.update', $category), [
            'name' => 'Updated Name',
            'slug' => 'updated-name',
            'image_source' => 'url',
            'image' => $category->image,
            'description' => 'Updated description',
            'sort_order' => 10,
            'is_active' => false,
        ])
        ->assertRedirect(route('admin.categories.index'));

    $category->refresh();

    expect($category->name)->toBe('Updated Name')
        ->and($category->slug)->toBe('updated-name')
        ->and($category->sort_order)->toBe(10)
        ->and($category->is_active)->toBeFalse();
});

test('admins can replace an uploaded image', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create([
        'image' => UploadedFile::fake()->image('old.jpg')->store('categories', 'public'),
    ]);

    $newFile = UploadedFile::fake()->image('new.jpg');

    $this->actingAs($admin)
        ->post(route('admin.categories.update', $category), [
            '_method' => 'put',
            'name' => $category->name,
            'slug' => $category->slug,
            'image_source' => 'upload',
            'image_file' => $newFile,
            'sort_order' => $category->sort_order,
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.categories.index'));

    $category->refresh();

    Storage::disk('public')->assertMissing('categories/old.jpg');
    Storage::disk('public')->assertExists($category->image);
});

test('admins can delete a category and its uploaded image', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $path = UploadedFile::fake()->image('category.jpg')->store('categories', 'public');
    $category = Category::factory()->create(['image' => $path]);

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertSoftDeleted('categories', [
        'id' => $category->id,
    ]);

    Storage::disk('public')->assertMissing($path);
});

test('category slug is generated from name when omitted', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Garden Tools',
            'slug' => '',
            'image_source' => 'url',
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Garden Tools',
        'slug' => 'garden-tools',
    ]);
});

test('category slug must be unique', function () {
    $admin = User::factory()->admin()->create();
    Category::factory()->create(['slug' => 'duplicate-slug']);

    $this->actingAs($admin)
        ->from(route('admin.categories.create'))
        ->post(route('admin.categories.store'), [
            'name' => 'Duplicate',
            'slug' => 'duplicate-slug',
            'image_source' => 'url',
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.categories.create'))
        ->assertSessionHasErrors('slug');
});

test('uploaded category images are rejected when invalid', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.categories.create'))
        ->post(route('admin.categories.store'), [
            'name' => 'Invalid Upload',
            'slug' => 'invalid-upload',
            'image_source' => 'upload',
            'image_file' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.categories.create'))
        ->assertSessionHasErrors('image_file');
});
