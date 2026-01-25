<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by module
        $permissions = [
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'approve-users',
            'assign-roles',

            // Contact Management
            'view-contacts',
            'create-contacts',
            'edit-contacts',
            'delete-contacts',
            'upload-contacts',
            'export-contacts',
            'import-contacts',

            // Contact Group Management
            'view-groups',
            'create-groups',
            'edit-groups',
            'delete-groups',
            'manage-group-contacts',

            // Campaign Management
            'view-campaigns',
            'create-campaigns',
            'edit-campaigns',
            'delete-campaigns',
            'send-campaigns',
            'schedule-campaigns',
            'cancel-campaigns',

            // Message Management
            'view-messages',
            'send-messages',
            'delete-messages',
            'view-message-logs',

            // System & Settings
            'view-dashboard',
            'view-analytics',
            'view-system-metrics',
            'manage-settings',
            'view-api-logs',

            // Permission & Role Management
            'manage-roles',
            'manage-permissions',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        // Create roles and assign permissions

        // 1. ADMIN ROLE - Full access to everything
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        $adminRole->syncPermissions(Permission::all());

        // 2. MODERATOR ROLE - Can manage content but not system settings or roles
        $moderatorRole = Role::firstOrCreate(
            ['name' => 'moderator'],
            ['guard_name' => 'web']
        );
        $moderatorRole->syncPermissions([
            // User Management (limited)
            'view-users',
            'edit-users',
            
            // Full Contact Management
            'view-contacts',
            'create-contacts',
            'edit-contacts',
            'delete-contacts',
            'upload-contacts',
            'export-contacts',
            'import-contacts',

            // Full Group Management
            'view-groups',
            'create-groups',
            'edit-groups',
            'delete-groups',
            'manage-group-contacts',

            // Full Campaign Management
            'view-campaigns',
            'create-campaigns',
            'edit-campaigns',
            'delete-campaigns',
            'send-campaigns',
            'schedule-campaigns',
            'cancel-campaigns',

            // Full Message Management
            'view-messages',
            'send-messages',
            'delete-messages',
            'view-message-logs',

            // Dashboard & Analytics
            'view-dashboard',
            'view-analytics',
            'view-system-metrics',
            'view-api-logs',
        ]);

        // 3. SUPPORT ROLE - Can view and assist, limited editing
        $supportRole = Role::firstOrCreate(
            ['name' => 'support'],
            ['guard_name' => 'web']
        );
        $supportRole->syncPermissions([
            // User Management (view only)
            'view-users',

            // Contact Management (view and create)
            'view-contacts',
            'create-contacts',
            'edit-contacts',
            'upload-contacts',
            'export-contacts',

            // Group Management (view and basic management)
            'view-groups',
            'create-groups',
            'manage-group-contacts',

            // Campaign Management (view only, can send)
            'view-campaigns',
            'send-campaigns',

            // Message Management (view and send)
            'view-messages',
            'send-messages',
            'view-message-logs',

            // Dashboard access
            'view-dashboard',
            'view-analytics',
            'view-system-metrics',
        ]);

        // 4. USER ROLE - Basic access, can only manage own contacts and campaigns
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['guard_name' => 'web']
        );
        $userRole->syncPermissions([
            // Contact Management (basic)
            'view-contacts',
            'create-contacts',
            'edit-contacts',
            'upload-contacts',

            // Group Management (basic)
            'view-groups',
            'create-groups',

            // Campaign Management (basic)
            'view-campaigns',
            'create-campaigns',
            'edit-campaigns',

            // Message Management (basic)
            'view-messages',
            'send-messages',

            // Dashboard access
            'view-dashboard',
        ]);

        $this->command->info('✓ Created ' . count($permissions) . ' permissions');
        $this->command->info('✓ Created 4 roles: admin, moderator, support, user');
        $this->command->info('✓ Assigned permissions to all roles');
        $this->command->line('');
        $this->command->warn('Permission Summary:');
        $this->command->line('• Admin: Full access (' . $adminRole->permissions->count() . ' permissions)');
        $this->command->line('• Moderator: Content management (' . $moderatorRole->permissions->count() . ' permissions)');
        $this->command->line('• Support: View & assist (' . $supportRole->permissions->count() . ' permissions)');
        $this->command->line('• User: Basic operations (' . $userRole->permissions->count() . ' permissions)');
    }
}
