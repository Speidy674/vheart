<?php

declare(strict_types=1);

namespace Database\Factories\Broadcaster;

use App\Enums\Broadcaster\RemovalRequestStatus;
use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\RemovalRequest;
use App\Models\Clip;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RemovalRequest>
 */
#[UseModel(RemovalRequest::class)]
class RemovalRequestFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'broadcaster_id' => Broadcaster::factory(),
            'clip_id' => Clip::factory(),
            'status' => RemovalRequestStatus::Pending,
            'claimed_by' => null,
            'claimed_at' => null,
            'resolved_by' => null,
            'resolved_at' => null,
        ];
    }
}
