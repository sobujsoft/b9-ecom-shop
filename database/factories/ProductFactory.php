<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 500, 15000),
            'compare_at_price' => fake()->optional()->randomFloat(2, 500, 20000),
            'stock_status' => fake()->randomElement(['in_stock', 'stock_out']),
            'is_best_seller' => false,
            'is_featured' => false,
            'is_active' => true,
            'sold_count' => fake()->numberBetween(0, 500),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product): void {
            ProductImage::query()->create([
                'product_id' => $product->id,
                'image_path' => 'photo-1505740420928-5e560c06d30e',
                'alt_text' => $product->name,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        });
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_status' => 'stock_out',
        ]);
    }
}
