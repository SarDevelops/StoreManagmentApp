<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
   public function run(): void
    {
        // reset cached roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // dashboard
            'dashboard.view',

            // users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // permissions
            'permissions.view',
            'permissions.assign',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web'
        ]);

        $owner = Role::firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'web'
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web'
        ]);

        $storeAssociate = Role::firstOrCreate([
            'name' => 'Store Associate',
            'guard_name' => 'web'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        // Admin & Owner = ALL permissions

        $admin->givePermissionTo(
            Permission::all()
        );

        $owner->givePermissionTo(
            Permission::all()
        );

        // Manager

        $manager->givePermissionTo([

            'dashboard.view',

            'users.view',
            'users.create',
            'users.update',

            'roles.view',
        ]);

        // Store Associate

        $storeAssociate->givePermissionTo([

            'dashboard.view',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Default Admin User
        |--------------------------------------------------------------------------
        */

        $user = User::firstOrCreate(
            [
                'email' => 'admin@example.com'
            ],
            [
                'name' => 'System Admin',

                'password' => Hash::make(
                    'password'
                ),
            ]
        );

        $user->assignRole('Admin');
    }
}
