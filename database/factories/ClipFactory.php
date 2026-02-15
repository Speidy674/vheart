<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Clip>
 */
class ClipFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'twitch_id' => fake()->uuid(),
            'title' => fake()->sentence(),
            'broadcaster_id' => User::factory(),
            'creator_id' => User::factory(),
            'submitter_id' => User::factory(),
            'category_id' => Category::factory(),
            'duration' => fake()->randomFloat(2, 5, 30),
            'date' => fake()->dateTimeBetween('-1 year'),
            'is_anonymous' => fake()->boolean(),
        ];
    }
}
