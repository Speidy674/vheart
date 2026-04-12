<?php

declare(strict_types=1);

namespace Database\Factories\Faq;

use App\Models\Faq\FaqEntry;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FaqEntry>
 */
#[UseModel(FaqEntry::class)]
class FaqEntryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->translations(['en', 'de'], [
                $this->faker->unique()->sentence(),
                $this->faker->unique()->sentence(),
            ]),
            'body' => $this->translations(['en', 'de'], [
                $this->faker->paragraph(),
                $this->faker->paragraph(),
            ]),
            'order' => $this->faker->numberBetween(1, 10),
            'published_at' => $this->faker->date(),
        ];
    }
}
