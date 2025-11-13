<?php

namespace Database\Factories;

use App\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['info', 'warning', 'error']),
            'message' => $this->faker->sentence(12),
            'created_at' => $this->faker->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
