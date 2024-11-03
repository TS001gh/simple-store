<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100), // Price between 10.00 and 100.00
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(), // Attach to existing category or create one if none exist
            'image' => 'products/placeholder.jpg', // Placeholder image path or you can use a static file
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
