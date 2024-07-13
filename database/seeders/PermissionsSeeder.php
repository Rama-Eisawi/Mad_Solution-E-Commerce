<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            //CRUD for users, categories, products
            'create_users', 'read_users', 'read_user', 'update_users', 'delete_users',
            'create_products', 'read_products', 'read_product', 'update_products', 'delete_products',
            'create_categories', 'read_categories', 'read_category', 'update_categories', 'delete_categories',
            //U for permissions
            'update_permissions',
            //RUD for order
            'read_orders', 'read_order', 'update_orders', 'delete_orders',
        ];

        foreach ($permissions as $permissionName) {
            Permission::create(['name' => $permissionName]);
        }
    }
}
