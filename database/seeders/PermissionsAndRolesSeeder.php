<?php

namespace Database\Seeders;

use App\Models\{Permission, Role};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Retrieve the roles
        $ownerRole = Role::where('name', 'Owner')->first();
        $otherRoles = Role::where('name', '!=', 'Owner')->get();
        $permissions = Permission::all();

        // Attach all permissions to the owner role with 'allow' authority
        foreach ($permissions as $permission) {
            $ownerRole->permissions()->attach($permission->id, ['authority' => 'allow']);
        }

        // Attach all permissions to other roles with 'deny' authority, then the owner will edit
        foreach ($otherRoles as $role) {
            foreach ($permissions as $permission) {
                $role->permissions()->attach($permission->id, ['authority' => 'deny']);
            }
        }
    }
}
