<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StreamType;

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
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'tokens_price' => $this->faker->numberBetween(50, 1000),
            'type_id' => StreamType::factory(),
            'date_expiration' => $this->faker->dateTimeBetween('now', '+1 year')
        ];
    }
}
