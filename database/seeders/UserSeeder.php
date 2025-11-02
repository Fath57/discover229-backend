<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles, ensure they exist from RolePermissionSeeder
        $adminSystemRole = Role::where('name', 'admin_system')->first();
        $adminAgencyRole = Role::where('name', 'admin_agency')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create Admin System User
        if ($adminSystemRole) {
            $adminUser = User::factory()->create([
                'firstname' => 'Admin',
                'lastname' => 'System',
                'email' => 'admin@discover229.com',
                'phone' => '+22990000001',
                'password' => Hash::make('password'),
            ]);
            $adminUser->assignRole($adminSystemRole);
        }

        // Create Admin Agency User
        if ($adminAgencyRole) {
            $agencyUser = User::factory()->create([
                'firstname' => 'Admin',
                'lastname' => 'Agency',
                'email' => 'agency@discover229.com',
                'phone' => '+22990000002',
                'password' => Hash::make('password'),
            ]);
            $agencyUser->assignRole($adminAgencyRole);
        }

        // Create a regular User
        if ($userRole) {
            $regularUser = User::factory()->create([
                'firstname' => 'Regular',
                'lastname' => 'User',
                'email' => 'user@discover229.com',
                'phone' => '+22990000003',
                'password' => Hash::make('password'),
            ]);
            $regularUser->assignRole($userRole);
        }

        $this->command->info('Default users created and assigned roles successfully!');
    }
}

