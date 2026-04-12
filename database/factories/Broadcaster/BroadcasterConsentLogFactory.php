<?php

declare(strict_types=1);

namespace Database\Factories\Broadcaster;

use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\BroadcasterConsentLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(BroadcasterConsentLog::class)]
class BroadcasterConsentLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'broadcaster_id' => Broadcaster::factory(),
            'state' => [],
            'changed_by' => User::factory(),
            'changed_at' => now(),
        ];
    }
}
