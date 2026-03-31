<?php

declare(strict_types=1);

namespace Database\Factories\Clip;

use App\Enums\Clips\CompilationClipClaimStatus;
use App\Models\Clip;
use App\Models\Clip\Compilation;
use App\Models\Clip\CompilationClip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompilationClip>
 */
class CompilationClipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clip_id' => Clip::factory(),
            'compilation_id' => Compilation::factory(),
            'added_by' => User::factory(),
            'added_at' => fake()->dateTime(),
            'claim_status' => CompilationClipClaimStatus::Pending,
            'claimed_by' => null,
            'claimed_at' => null,
        ];
    }
}
