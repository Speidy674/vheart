<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ClipVoteType;
use App\Models\Clip;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vote>
 */
#[UseModel(Vote::class)]
class VoteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clip_id' => Clip::factory(),
            'user_id' => User::factory(),
            'type' => ClipVoteType::Public,
            'voted' => fake()->boolean(),
        ];
    }
}
