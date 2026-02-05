<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq\FaqEntry;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        if (FaqEntry::count() > 0) {
            return;
        }

        FaqEntry::create([
            'title' => [
                'en' => 'Is this a example question?',
                'de' => 'Ist dies eine beispiel frage?',
            ],
            'body' => [
                'en' => 'Yes, this is a example question to have something to test in local environment. We can later rewrite the seeder to initially seed the actual questions later.',
                'de' => 'Ja dies ist eine beispiel frage um in lokaler umgebung etwas zum testen zu haben. Wir können später den seeder umschreibem um die tatsächlichen fragen zu seeden sobald wir die haben.',
            ],
            'published_at' => now(),
            'order' => -1,
        ]);

        FaqEntry::create([
            'title' => [
                'en' => 'Is this a english only example question?',
            ],
            'body' => [
                'en' => 'Yes, this question is only available in English.',
            ],
            'published_at' => now(),
            'order' => -1,
        ]);

        FaqEntry::create([
            'title' => [
                'de' => 'Ist dies eine deutsche beispiel frage?',
            ],
            'body' => [
                'de' => 'Ja diese frage ist nur auf deutsch verfügbar.',
            ],
            'published_at' => now(),
            'order' => -1,
        ]);

        FaqEntry::factory()->count(10)->create();
    }
}
