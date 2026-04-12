<?php

declare(strict_types=1);

namespace Database\Factories\Broadcaster;

use App\Models\Broadcaster\Broadcaster;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
