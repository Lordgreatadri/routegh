<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSupportUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedUser(
            email: 'admin@routegh.com',
            data: [
                'name' => 'System Admin',
                'phone' => '0245309876',
                'role' => 'admin',
                'company_name' => 'Route GH',
                'status' => 'approved',
                'approved_at' => now(),
                'is_client' => false,
            ],
            password: env('SYSTEM_ADMIN_PASSWORD', 'password')
        );

        $this->seedUser(
            email: 'support@routegh.com',
            data: [
                'name' => 'System Support',
                'phone' => '0553456400',
                'role' => 'admin',
                'company_name' => 'Route GH',
                'status' => 'approved',
                'approved_at' => now(),
                'is_client' => false,
            ],
            password: env('SYSTEM_SUPPORT_PASSWORD', 'password')
        );
    }



    protected function seedUser(string $email, array $data, string $password): void
    {
        // Get existing password hash (if user exists)
        $existingPassword = User::where('email', $email)->value('password');

        $user = User::updateOrCreate(
            ['email' => $email],
            array_merge($data, [
                'password' => $existingPassword ?? Hash::make($password),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ])
        );

        if ($user->wasRecentlyCreated) {
            $this->command->info("User created: {$email}");
        } else {
            $this->command->info("User updated: {$email} (password preserved)");
        }




// 
        // $user = User::updateOrCreate(
            // ['email' => $email],
            // array_merge($data, [
                // 'email_verified_at' => now(),
                // 'phone_verified_at' => now(),
            // ])
        // );
// 
        // Only set password on first creation
        // if ($user->wasRecentlyCreated) {
            // $user->update([
                // 'password' => Hash::make($password),
            // ]);
            // $this->command->info("User created: {$email}");
        // } else {
            // $this->command->info("User already exists: {$email}");
        // }
    }
}
