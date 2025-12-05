<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = $this->createPermissions();

        // Create Roles
        $roles = $this->createRoles();

        // Assign Permissions to Roles
        $this->assignPermissionsToRoles($roles, $permissions);

        // Create Admin User
        $this->createAdminUser($roles);
    }

    private function createPermissions(): array
    {
        $permissionsList = [
            // Dashboard
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'group' => 'Dashboard'],

            // Users
            ['name' => 'view_users', 'display_name' => 'View Users', 'group' => 'Users'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'group' => 'Users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'group' => 'Users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'group' => 'Users'],

            // Products
            ['name' => 'view_products', 'display_name' => 'View Products', 'group' => 'Products'],
            ['name' => 'create_products', 'display_name' => 'Create Products', 'group' => 'Products'],
            ['name' => 'edit_products', 'display_name' => 'Edit Products', 'group' => 'Products'],
            ['name' => 'delete_products', 'display_name' => 'Delete Products', 'group' => 'Products'],

            // Orders
            ['name' => 'view_orders', 'display_name' => 'View Orders', 'group' => 'Orders'],
            ['name' => 'create_orders', 'display_name' => 'Create Orders', 'group' => 'Orders'],
            ['name' => 'edit_orders', 'display_name' => 'Edit Orders', 'group' => 'Orders'],
            ['name' => 'delete_orders', 'display_name' => 'Delete Orders', 'group' => 'Orders'],

            // RFQs
            ['name' => 'view_rfqs', 'display_name' => 'View RFQs', 'group' => 'RFQ'],
            ['name' => 'create_rfqs', 'display_name' => 'Create RFQs', 'group' => 'RFQ'],
            ['name' => 'edit_rfqs', 'display_name' => 'Edit RFQs', 'group' => 'RFQ'],
            ['name' => 'delete_rfqs', 'display_name' => 'Delete RFQs', 'group' => 'RFQ'],
            ['name' => 'submit_quotes', 'display_name' => 'Submit Quotes', 'group' => 'RFQ'],
            ['name' => 'view_quotes', 'display_name' => 'View Quotes', 'group' => 'RFQ'],
            ['name' => 'edit_quotes', 'display_name' => 'Edit Quotes', 'group' => 'RFQ'],

            // Markets
            ['name' => 'view_markets', 'display_name' => 'View Markets', 'group' => 'Markets'],
            ['name' => 'create_markets', 'display_name' => 'Create Markets', 'group' => 'Markets'],
            ['name' => 'edit_markets', 'display_name' => 'Edit Markets', 'group' => 'Markets'],
            ['name' => 'delete_markets', 'display_name' => 'Delete Markets', 'group' => 'Markets'],

            // Supplier Portal
            ['name' => 'access_supplier_portal', 'display_name' => 'Access Supplier Portal', 'group' => 'Supplier'],
            ['name' => 'manage_supplier_invitations', 'display_name' => 'Manage Invitations', 'group' => 'Supplier'],

            // Settings
            ['name' => 'view_settings', 'display_name' => 'View Settings', 'group' => 'Settings'],
            ['name' => 'edit_settings', 'display_name' => 'Edit Settings', 'group' => 'Settings'],

            // Logs
            ['name' => 'view_logs', 'display_name' => 'View Logs', 'group' => 'Logs'],

            // Privacy/Roles
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'group' => 'Privacy'],
            ['name' => 'manage_permissions', 'display_name' => 'Manage Permissions', 'group' => 'Privacy'],
        ];

        $permissions = [];
        foreach ($permissionsList as $perm) {
            $permissions[$perm['name']] = Permission::create($perm);
        }

        return $permissions;
    }

    private function createRoles(): array
    {
        $rolesList = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access - can do everything',
                'is_system' => true,
            ],
            [
                'name' => 'buyer',
                'display_name' => 'Buyer',
                'description' => 'Can create RFQs, view quotes, and place orders',
                'is_system' => true,
            ],
            [
                'name' => 'seller',
                'display_name' => 'Seller',
                'description' => 'Can manage markets and sell products',
                'is_system' => true,
            ],
            [
                'name' => 'supplier',
                'display_name' => 'Supplier',
                'description' => 'Can respond to RFQs and submit quotes',
                'is_system' => true,
            ],
        ];

        $roles = [];
        foreach ($rolesList as $role) {
            $roles[$role['name']] = Role::create($role);
        }

        return $roles;
    }

    private function assignPermissionsToRoles(array $roles, array $permissions): void
    {
        // Admin - All permissions
        $roles['admin']->givePermissionTo(...array_keys($permissions));

        // Buyer permissions
        $roles['buyer']->givePermissionTo(
            'view_dashboard',
            'view_products',
            'view_orders',
            'create_orders',
            'view_rfqs',
            'create_rfqs',
            'edit_rfqs',
            'view_quotes',
            'view_markets',
            'view_settings',
            'view_logs'
        );

        // Seller permissions
        $roles['seller']->givePermissionTo(
            'view_dashboard',
            'view_products',
            'create_products',
            'edit_products',
            'view_orders',
            'view_markets',
            'create_markets',
            'edit_markets',
            'view_settings',
            'view_logs'
        );

        // Supplier permissions
        $roles['supplier']->givePermissionTo(
            'view_dashboard',
            'view_products',
            'view_rfqs',
            'submit_quotes',
            'view_quotes',
            'edit_quotes',
            'access_supplier_portal',
            'manage_supplier_invitations',
            'view_settings'
        );
    }

    private function createAdminUser(array $roles): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@dpanel.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'is_buyer' => true,
            'is_seller' => true,
            'is_supplier' => true,
            'role' => 'admin',
            'is_active' => true,
        ]);

        $admin->roles()->attach($roles['admin']);

        $this->command->info('✅ Admin user created: admin@dpanel.test / password');
    }
}
