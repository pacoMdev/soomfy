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
            'name' => 'Marc',
            'surname1' => 'Spector',
            'alias' => 'cavallero Luna',
            'email' => 'admin@demo.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4147,
            'longitude' => 2.0156, // Molins de Rei
        ]);
        $user->addMedia(file: storage_path(DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.'7e521815ea63e7e6fda995e9953fdebc.jpg'))->preservingOriginal()->toMediaCollection('images');


        // Crear roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);

        // Asignar permisos al rol user
        $permissionsUser = [
            'product-list',
            'product-create',
            'product-edit',
            'product-delete'
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
        $user->addMedia(file: storage_path(path: DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.'qDXOKMbDKBEMfKzPCFRW5Z3Jv4JCsTLOiJ44BztKLGY.webp'))->preservingOriginal()->toMediaCollection('images');

        $user->assignRole([$roleAdmin->id]);

        // Crear usuario normal (Pallejà)
        $user = User::create([
            'id' => 3,
            'name' => 'Dexter',
            'surname1' => 'Morgan',
            'alias' => 'DMorgan',
            'email' => 'dexter@gmail.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4250,
            'longitude' => 1.9873, // Pallejà
        ]);
        $user->addMedia(file: storage_path(path: DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.'dexter-dexter-morgan.png'))->preservingOriginal()->toMediaCollection('images');

        $user->assignRole([$roleUser->id]);
    }
}
