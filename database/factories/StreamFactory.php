<?php

namespace Database\Factories;

use App\Models\StreamType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StreamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'tokens_price' => $this->faker->numberBetween(50, 1000),
            'type_id' => StreamType::factory(),
            'date_expiration' => $this->faker->dateTimeBetween('now', '+1 year')
        ];
    }
}
