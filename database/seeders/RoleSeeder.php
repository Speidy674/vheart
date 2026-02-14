<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleCount = Role::count();

        $superadmin = Role::firstOrCreate(
            ['id' => 0],
            [
                'name' => 'Super Admin',
                'weight' => 99999999999,
                'public' => false,
                'desc' => 'The Role to Role them all',
            ]
        );

        $allPermissions = collect(Permission::cases())->pluck('value');

        DB::table('role_permissions')->insertOrIgnore(
            $allPermissions->map(fn (string $permission) => [
                'role_id' => $superadmin->id,
                'permission' => $permission,
            ])->toArray()
        );

        if ($roleCount > 0) {
            return;
        }

        Role::firstOrCreate(
            [
                'name' => 'Administrator',
            ],
            [
                'weight' => 100,
                'public' => true,
            ]
        );

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
