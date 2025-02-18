<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear usuario Admin David (Molins de Rei)
        $user = User::create([
            'id' => 1,
            'name' => 'David',
            'surname1' => 'Herrera',
            'alias' => 'dherrera',
            'email' => 'admin@demo.com',
            'password' => bcrypt('12345678'),
            'latitude' => 41.4147,
            'longitude' => 2.0156, // Molins de Rei
        ]);

        // Crear roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);

        // Asignar permisos al rol user
        $permissionsUser = [
            'post-list',
            'post-create',
            'post-edit',
            'post-delete'
        ];
        $roleUser->syncPermissions($permissionsUser);

        // Asignar todos los permisos al rol admin
        $permissionsAdmin = Permission::pluck('id', 'id')->all();
        $roleAdmin->syncPermissions($permissionsAdmin);
        $user->assignRole([$roleAdmin->id]);

        // Crear usuario Admin Supremo (Barcelona)
        $user = User::create([
            'id' => 2,
            'name' => 'Admin',
            'surname1' => 'Thor',
            'alias' => 'AdminSupremo',
            'email' => 'admin@admin.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.3874,
            'longitude' => 2.1686, // Barcelona
        ]);
        $user->assignRole([$roleAdmin->id]);

        // Crear usuario normal (Pallejà)
        $user = User::create([
            'id' => 3,
            'name' => 'User',
            'surname1' => 'User',
            'alias' => 'user',
            'email' => 'user@demo.com',
            'password' => bcrypt('12345678'),
            'latitude' => 41.4250,
            'longitude' => 1.9873, // Pallejà
        ]);
        $user->assignRole([$roleUser->id]);
    }
}
