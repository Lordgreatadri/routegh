<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create System Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@routegh.com'],
            [
                'name' => 'System Admin',
                'phone' => '0245309876',
                'password' => Hash::make('p@s$word'), // Change in production!
                'role' => 'admin',
                'status' => 'approved',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'is_client' => false,
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('System Admin created: admin@routegh.com (password: p@s$word)');
        } else {
            $this->command->info('System Admin already exists.');
        }

        // Create System Support
        $support = User::updateOrCreate(
            ['email' => 'support@routegh.com'],
            [
                'name' => 'System Support',
                'phone' => '0553456400',
                'password' => Hash::make('Pa$$w0rd'), // Change in production!
                'role' => 'admin', // Using admin role since support is not in enum
                'status' => 'approved',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'is_client' => false,
            ]
        );

        if ($support->wasRecentlyCreated) {
            $this->command->info('System Support created: support@routegh.com (password: Pa$$w0rd)');
        } else {
            $this->command->info('System Support already exists.');
        }
    }
}
