<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'summary' => $this->faker->text(150),
            'body' => collect($this->faker->paragraphs(rand(3, 6)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'image' => 'news/' . $this->faker->numberBetween(1, 5) . '.jpg',
            'is_active' => $this->faker->boolean(90),
            'views' => $this->faker->numberBetween(10, 5000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
