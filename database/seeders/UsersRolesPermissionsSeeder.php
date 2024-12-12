<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersRolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Permissions
        $permissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'manage users',
            'view admin panel'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Create Roles
        $roles = [
            'admin' => [
                'create posts',
                'edit posts',
                'delete posts',
                'manage users',
                'view admin panel'
            ],
            'editor' => [
                'create posts',
                'edit posts'
            ],
            'user' => []
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Create Default Users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'password',
                'role' => 'admin'
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'password' => 'password',
                'role' => 'editor'
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => 'password',
                'role' => 'user'
            ],
            [
                'name' => 'Regular User 2',
                'email' => 'user2@example.com',
                'password' => 'password',
                'role' => 'user'
            ],
            [
                'name' => 'Regular User 3',
                'email' => 'use3@example.com',
                'password' => 'password',
                'role' => 'user'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password'])
                ]
            );

            // Assign role
            $user->syncRoles([$userData['role']]);
        }
    }
}
