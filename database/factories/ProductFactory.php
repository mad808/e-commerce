<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = ucwords($this->faker->words(3, true));
        $price = $this->faker->randomFloat(2, 10, 500);

        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . rand(1, 1000),
            'barcode' => $this->faker->unique()->ean13(),
            'description' => $this->faker->paragraph,
            'price' => $price,
            'cost_price' => $price * $this->faker->randomFloat(2, 0.6, 0.8),
            'discount_percent' => $this->faker->randomElement([0, 10, 20, 30, 50]),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => 'products/' . $this->faker->numberBetween(1, 30) . '.png',
            'is_active' => true,
            'views' => $this->faker->numberBetween(0, 500),
        ];
    }

    /**
     * Configure the model factory.
     * This runs AFTER the product is created to attach attributes.
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $attributes = Attribute::inRandomOrder()->take(rand(1, 4))->get();

            if ($attributes->isNotEmpty()) {
                foreach ($attributes as $attribute) {
                    $value = '';

                    $name = strtolower($attribute->name);

                    if (str_contains($name, 'color')) {
                        $value = $this->faker->colorName();
                    } elseif (str_contains($name, 'size')) {
                        $value = $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL', '40', '42', '44']);
                    } elseif (str_contains($name, 'material')) {
                        $value = $this->faker->randomElement(['Cotton', 'Polyester', 'Leather', 'Wood', 'Metal']);
                    } elseif (str_contains($name, 'weight')) {
                        $value = $this->faker->numberBetween(100, 5000) . 'g';
                    } else {
                        $value = $this->faker->word();
                    }

                    $product->attributes()->attach($attribute->id, ['value' => $value]);
                }
            }
        });
    }
}
