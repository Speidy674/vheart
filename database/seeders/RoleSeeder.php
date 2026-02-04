<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Role::count() > 0) {
            return;
        }

        $admin = Role::firstOrCreate(
            [
                'name' => 'Administrator',
            ],
            [
                'weight' => 100,
                'public' => true,
            ]
        );

        $admin->permissions()
            ->createMany(collect(Permission::cases())->map(fn (Permission $p) => ['permission' => $p->value])->toArray());

        Role::firstOrCreate(
            [
                'name' => 'Community Manager',
            ],
            [
                'weight' => 90,
                'public' => true,
            ]
        );
        Role::firstOrCreate(
            [
                'name' => 'Moderator',
            ],
            [
                'weight' => 80,
                'public' => true,
            ]
        );
        Role::firstOrCreate(
            [
                'name' => 'Cutter',
            ],
            [
                'weight' => 70,
                'public' => true,
            ]
        );
        Role::firstOrCreate(
            [
                'name' => 'IT',
            ],
            [
                'weight' => 60,
                'public' => true,
            ]
        );
        Role::firstOrCreate(
            [
                'name' => 'Jury',
            ],
            [
                'weight' => 50,
                'public' => true,
            ]
        );
    }
}
