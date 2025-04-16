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
        
        // Crear usuario con ubicación específica para pruebas de geolocalización
        $usuarioCercano = User::create([
            'id' => 4,
            'name' => 'Usuario',
            'surname1' => 'Cercano',
            'alias' => 'UCercano',
            'email' => 'usuario.cercano@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4154752,
            'longitude' => 1.9955712, // Ubicación específica proporcionada
        ]);
        
        $usuarioCercano->assignRole([$roleUser->id]);
        
        // Usuarios con ubicaciones cercanas a Barcelona (dentro de 20km)
        
        // Usuario en Badalona (10km de Barcelona)
        $usuarioBadalona = User::create([
            'id' => 5,
            'name' => 'Carlos',
            'surname1' => 'Mendoza',
            'alias' => 'CMendoza',
            'email' => 'carlos.mendoza@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4500,
            'longitude' => 2.2474, // Badalona
        ]);
        $usuarioBadalona->assignRole([$roleUser->id]);
        
        // Usuario en L'Hospitalet de Llobregat (7km de Barcelona)
        $usuarioHospitalet = User::create([
            'id' => 6,
            'name' => 'Marina',
            'surname1' => 'Torres',
            'alias' => 'MTorres',
            'email' => 'marina.torres@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.3661,
            'longitude' => 2.1164, // L'Hospitalet de Llobregat
        ]);
        $usuarioHospitalet->assignRole([$roleUser->id]);
        
        // Usuario en Sant Cugat del Vallès (15km de Barcelona)
        $usuarioSantCugat = User::create([
            'id' => 7,
            'name' => 'Antonio',
            'surname1' => 'Reyes',
            'alias' => 'AReyes',
            'email' => 'antonio.reyes@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4667,
            'longitude' => 2.0833, // Sant Cugat del Vallès
        ]);
        $usuarioSantCugat->assignRole([$roleUser->id]);
        
        // Usuario en Castelldefels (18km de Barcelona)
        $usuarioCastelldefels = User::create([
            'id' => 8,
            'name' => 'Marta',
            'surname1' => 'Etxebarria',
            'alias' => 'MEtxe',
            'email' => 'marta.etxebarria@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.2800,
            'longitude' => 1.9767, // Castelldefels
        ]);
        $usuarioCastelldefels->assignRole([$roleUser->id]);
        
        // Usuario en Mataró (20km de Barcelona)
        $usuarioMataro = User::create([
            'id' => 9,
            'name' => 'Pablo',
            'surname1' => 'Sanchez',
            'alias' => 'PSanchez',
            'email' => 'pablo.sanchez@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.5400,
            'longitude' => 2.4447, // Mataró
        ]);
        $usuarioMataro->assignRole([$roleUser->id]);
    }
}
