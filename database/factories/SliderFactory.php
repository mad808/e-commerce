<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SliderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image' => 'sliders/' . $this->faker->numberBetween(1, 20) . '.png',
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->sentence(6),
            'link' => '#',
            'type' => $this->faker->randomElement(['home_main', 'product_detail_banner']),
            'sort_order' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
