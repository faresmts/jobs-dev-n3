<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => random_int(1, 10000),
            'title' => fake()->realText(70),
            'url' => fake()->url,
            'summary' => fake()->realText(200),
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];
    }
}
