<?php

declare(strict_types=1);

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Models\Category;
use App\Models\Clip;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

describe('Banned Category', function () {
    it('shows clips with normal category', function () {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        $user->broadcaster()->create([
            'consent' => [BroadcasterConsent::Compilations],
        ]);

        Clip::factory()->recycle($category)->recycle($user)->create();

        $this->assertModelExists($category);
        $this->assertDatabaseCount('clips', 1);
        $this->assertCount(1, Clip::all());
    });

    it('hides clips with banned categories', function () {
        $category = Category::factory()->isBanned()->create();
        $user = User::factory()->create();

        $user->broadcaster()->create([
            'consent' => [BroadcasterConsent::Compilations],
        ]);

        Clip::factory()->recycle($category)->recycle($user)->create();

        $this->assertModelExists($category);
        $this->assertDatabaseCount('clips', 1);
        $this->assertCount(0, Clip::all());
    });

    it('does not affect clips without any category', function () {
        $user = User::factory()->create();
        $user->broadcaster()->create([
            'consent' => [BroadcasterConsent::Compilations],
        ]);

        Clip::factory()->recycle($user)->create([
            'category_id' => 1,
        ]);

        $this->assertNull(Category::find(1));
        $this->assertDatabaseCount('clips', 1);
        $this->assertCount(1, Clip::all());
    });
});
