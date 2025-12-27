<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'client')->inRandomOrder()->first()->id ?? User::factory(),
            'full_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'total_price' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'notes' => $this->faker->optional()->sentence,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
