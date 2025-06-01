<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_be_indexed()
    {
        // Esto asegura que existe un rol "user" antes de crear el usuario
        $role = Role::firstOrCreate(['name' => 'user']);
        
        $usuario1 = User::create([
            'name' => 'Usuario 1',
            'surname1' => 'Apellido1',
            'alias' => 'user1',
            'email' => 'usuario1@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4147,
            'longitude' => 2.0156,
            'role_id' => $role->id
        ]);
        
        $usuario2 = User::create([
            'name' => 'Usuario 2',
            'surname1' => 'Apellido2',
            'alias' => 'user2',
            'email' => 'usuario2@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.3874,
            'longitude' => 2.1686,
            'role_id' => $role->id
        ]);
        
        $users = User::all();
        
        $this->assertContains($usuario1->id, $users->pluck('id')->toArray());
        $this->assertContains($usuario2->id, $users->pluck('id')->toArray());
    }
    
    public function test_user_can_be_created()
    {
        // Esto asegura que existe un rol "user" antes de crear el usuario
        $role = Role::firstOrCreate(['name' => 'user']);
        
        $userData = [
            'name' => 'Usuario Prueba',
            'surname1' => 'Apellido Prueba',
            'alias' => 'usertest',
            'email' => 'usuarioprueba@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4250,
            'longitude' => 1.9873,
            'role_id' => $role->id
        ];

        $user = User::create($userData);
        $userId = $user->id;

        $this->assertDatabaseHas('users', [
            'id' => $userId,
        ]);

        $this->assertEquals($user->id, $userId);
    }

    public function test_user_can_be_updated()
    {
        // Esto asegura que existe un rol "user" antes de crear el usuario
        $role = Role::firstOrCreate(['name' => 'user']);
        
        $user = User::create([
            'name' => 'Usuario_Original',
            'surname1' => 'Apellido_Original',
            'alias' => 'original',
            'email' => 'original@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4147,
            'longitude' => 2.0156,
            'role_id' => $role->id
        ]);
        
        $userId = $user->id;
        
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'Usuario_Original',
        ]);
    
        $user->update([
            'name' => 'Usuario_Actualizado',
            'surname1' => 'Apellido_Actualizado',
            'email' => 'actualizado@example.com'
        ]);
        
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'Usuario_Actualizado',
            'surname1' => 'Apellido_Actualizado',
        ]);
        
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
            'name' => 'Usuario_Original',
        ]);
    }
    
    public function test_user_can_be_deleted()
    {
        // Esto asegura que existe un rol "user" antes de crear el usuario
        $role = Role::firstOrCreate(['name' => 'user']);
        
        $user = User::create([
            'name' => 'Usuario a eliminar',
            'surname1' => 'Apellido Eliminar',
            'alias' => 'userdelete',
            'email' => 'eliminar@example.com',
            'password' => bcrypt('Qwert12345'),
            'latitude' => 41.4147,
            'longitude' => 2.0156,
            'role_id' => $role->id
        ]);
        
        $userId = $user->id;
        
        $this->assertDatabaseHas('users', [
            'id' => $userId,
        ]);
    
        $user->delete();
    
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
        ]);
    }
}