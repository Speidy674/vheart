<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Audit>
 */
#[UseModel(Audit::class)]
class AuditFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
