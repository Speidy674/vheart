<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Clip\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        // One time Setup
        if (Tag::count() > 0) {
            return;
        }

        $tags = [
            ['Comedy Gold', 'Comedy Gold'],
            ['Lost Moment', 'Lost Moment'],
            ['Epic Fail', 'Epic Fail'],
            ['Perfect Timing', 'Perfect Timing'],
            ['Epic Win', 'Epic Win'],
            ['Rage-Mode', 'Rage-Mode'],
            ['Skilled Moment', 'Skilled Moment'],
            ['Jumpscare', 'Jumpscare'],
            ['Wholesome', 'Wholesome'],
            ['Realtalk', 'Realtalk'],
            ['Tech Fail', 'Tech Fail'],
            ['Bug', 'Bug'],
            ['Kreativ', 'Creative'],
            ['Sprachfehler', 'Speech errors'],
            ['Chat Interaktion', 'Chat Interaction'],
            ['Weisheiten', 'Wisdom'],
            ['Storytime', 'Storytime'],
            ['Was passiert gerade?', 'What\'s happening right now?'],
            ['Musik', 'Music'],
            ['Kollaboration', 'Collaboration'],
            ['Kunst', 'Art'],
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => ['de' => $tag[0], 'en' => $tag[1]],
            ]);
        }
    }
}
