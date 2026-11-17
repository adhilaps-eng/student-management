<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Manage student management operations',
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Manage assigned classes and academic activities',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Access personal academic information',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'updated_at' => $now,
                ] 
            );
        }
    }
}
