<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // create permissions
        $permissions = [
            'access' => [
                'access backoffice'
            ],
            'users' => [
                'view users',
                'create user',
                'view user',
                'edit user',
                'delete user',
            ],
            'employees' => [
                'view employees',
                'create employee',
                'view employee',
                'edit employee',
                'delete employee',
            ],
            'attendances' => [
                'view attendances',
                'create attendace',
                'view attendace',
                'edit attendance',
                'delete attendance',
            ],
            'roles' => [
                'view roles',
                'create role',
                'view role',
                'edit role',
                'delete role',
            ],
            'payrolls' => [
                'view payrolls',
                'create payroll',
                'view payroll',
                'edit payroll',
                'delete payroll',
            ],
            'expenses' => [
                'view expenses',
                'create expense',
                'view expense',
                'edit expense',
                'delete expense',
            ],
            'offers' => [
                'view offers',
                'create offer',
                'view offer',
                'edit offer',
                'delete offer',
            ],
            'plans' => [
                'view plans',
                'create plan',
                'view plan',
                'edit plan',
                'delete plan',
            ],
            'transactions' => [
                'view transactions',
                'create transaction',
                'view transaction',
                'edit transaction',
                'delete transaction',
            ],
            'subscriptions' => [
                'view subscriptions',
                'create subscription',
                'view subscription',
                'edit subscription',
                'delete subscription',
            ],
            'purchases' => [
                'view purchases',
                'create purchase',
                'view purchase',
                'edit purchase',
                'delete purchase',
            ],
            'categories' => [
                'view categories',
                'create category',
                'view category',
                'edit category',
                'delete category',
            ],
            'tags' => [
                'view tags',
                'create tag',
                'view tag',
                'edit tag',
                'delete tag',
            ],
            'contents' => [
                'view contents',
                'create content',
                'view content',
                'edit content',
                'delete content',
            ],
            'resources' => [
                'view resources',
                'create resource',
                'view resource',
                'edit resource',
                'delete resource',
            ],
            'permissions' => [
                'view permissions',
                'view permission',
            ],
            'roles' => [
                'view roles',
                'create role',
                'view role',
                'edit role',
                'delete role',
            ],
        ];

        foreach (collect($permissions) as $key => $group) {
            if (count($group)) {
                foreach ($group as $permission) {
                    Permission::create([
                        'name' => $permission,
                        'guard_name' => 'api',
                        'group' => $key,
                    ]);
                }
            }
        }

        // create roles
        $roles = [
            'admin',
            'user',
            'test_role'
        ];
        foreach ($roles as $key => $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'api'
            ]);
        }

        // assign permissions to roles
        $all_permissions = Permission::all();
        $role_admin = Role::where('name', 'admin')->first()->syncPermissions($all_permissions);
        $role_user = Role::where('name', 'user')->first()->givePermissionTo('view users','view user', 'edit user');

        // assign role to users
        // $users = User::all();
        // foreach ($users as $key => $user) {
        //     if ($key < 1) {
        //         $user->assignRole($role_admin);
        //     } else {
        //         $user->assignRole($role_user);
        //     }
        // }


        $admin = factory(User::class)->create([
            // 'firstname' => 'John',
            // 'lastname' => 'Doe',
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$PAbdMfnXKbrE1FPHY1Th.urgxPr76KEDonWCauhuW5Nv9k3vYhjXO', // admin123
            // 'remember_token' => Str::random(10),
            // 'profile_image' => config('app.url') . 'images/default.png',
            // 'gender' => 'Male',
            // 'age' => 20,
        ]);
        $admin->assignRole($roles[0]);
        // $this->addUserDefaultLibraries($admin);

        $user = factory(User::class)->create([
            // 'firstname' => 'Jane',
            // 'lastname' => 'Doe',
            'name' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$ek6pHUyeISJ54T4pMi6t..V5/ouC9Y8s/phEcnocEAclfp7Jq7nDu', // user123
            // 'remember_token' => Str::random(10),
            // 'profile_image' => config('app.url') . 'images/default.png',
            // 'gender' => 'Female',
            // 'age' => 20,
        ]);
        $user->assignRole($roles[1]);
        // $this->addUserDefaultLibraries($user);
    }
}
