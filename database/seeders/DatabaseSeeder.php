<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'id' => 0,
        ], [
            'name' => 'System',
            'avatar_url' => 'https://api.dicebear.com/9.x/pixel-art/svg?seed='.Str::random(),
        ]);

        $this->call([
            RoleSeeder::class,
            TagSeeder::class,
            InitialEpisodeSeeder::class,
            FaqSeeder::class,
        ]);

        // Kindly wipe unused permission pivots on deployment
        DB::table('role_permissions')->whereNotIn('permission', Permission::cases())->delete();
    }
}
