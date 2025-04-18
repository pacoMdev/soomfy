<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-all',
            'product-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'estado-list',
            'estado-create',
            'estado-edit',
            'estado-delete',
            'transactions-list',
            'transactions-create',
            'transactions-edit',
            'transactions-delete',
            'opinions-list',
            'opinions-create',
            'opinions-edit',
            'opinions-delete',     
            'establecimiento-list',
            'establecimiento-create',
            'establecimiento-edit',
            'establecimiento-delete',     
            'shippingAddress-list',
            'shippingAddress-create',
            'shippingAddress-edit',
            'shippingAddress-delete',
            'establecimientoTransaction-list',
            'establecimientoTransaction-create',
            'establecimientoTransaction-edit',
            'establecimientoTransaction-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
