<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Clip;
use App\Models\User;
use function PHPUnit\Framework\assertCount;

beforeEach(function () {
    $this->withoutVite();
});

describe('Banned Category', function () {
    it('shows clips with normal category', function () {
        $category = Category::factory()->create();
        $user = User::factory()->withClipPermission()->create();

        Clip::factory()->recycle($category)->recycle($user)->create();

        assertCount(1, Clip::all());
    });

    it('hides clips with banned categories', function () {
        $category = Category::factory()->isBanned()->create();
        $user = User::factory()->withClipPermission()->create();

        Clip::factory()->recycle($category)->recycle($user)->create();

        assertCount(0, Clip::all());
    });
});
