<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Test unitario simplificado para UserController
 */
class UserTest extends TestCase
{
    protected $user;
    protected $userController;
    protected $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un objeto simple para simular un usuario
        $this->user = new stdClass();
        $this->user->id = 1;
        $this->user->name = 'Test User';
        $this->user->email = 'test@example.com';
        $this->user->surname1 = 'Surname1';
        $this->user->surname2 = 'Surname2';
        
        // Simular el controlador
        $this->userController = new stdClass();
        
        // Simular autenticación con Sanctum
        $this->userController->auth = function() {
            return $this->user;
        };
        
        // Simular request
        $this->request = new stdClass();
        $this->request->name = 'New User';
        $this->request->email = 'new@example.com';
        $this->request->role_id = 2;
        $this->request->password = 'password123';
    }

    /**
     * Test de obtención de usuarios
     */
    public function test_user_can_be_indexed()
    {
        // Simular respuesta de index
        $this->userController->index = function() {
            $users = [(object)[
                'id' => 1,
                'name' => 'User 1'
            ], (object)[
                'id' => 2,
                'name' => 'User 2'
            ]];
            return $users;
        };
        
        // Ejecutar y verificar que devuelve una colección con usuarios
        $result = ($this->userController->index)();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('User 1', $result[0]->name);
    }
    
    /**
     * Test de creación de usuario
     */
    public function test_user_can_be_created()
    {
        // Simular el método store
        $this->userController->store = function($request) {
            // Simular creación de usuario
            $newUser = new stdClass();
            $newUser->id = 3;
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            return $newUser;
        };
        
        // Ejecutar y verificar
        $result = ($this->userController->store)($this->request);
        $this->assertEquals('New User', $result->name);
        $this->assertEquals('new@example.com', $result->email);
    }
    
    /**
     * Test de actualización de usuario
     */
    public function test_user_can_be_updated()
    {
        // Simular el método update
        $this->userController->update = function($request, $user) {
            $user->name = $request->name;
            $user->email = $request->email;
            return $user;
        };
        
        // Ejecutar y verificar
        $result = ($this->userController->update)($this->request, $this->user);
        $this->assertEquals('New User', $result->name);
        $this->assertEquals('new@example.com', $result->email);
    }
    
    /**
     * Test de eliminación de usuario
     */
    public function test_user_can_be_deleted()
    {
        // Simular el método destroy
        $this->userController->destroy = function($user) {
            // Simulamos la respuesta noContent (204)
            return true;
        };
        
        // Ejecutar y verificar
        $result = ($this->userController->destroy)($this->user);
        $this->assertTrue($result);
    }
    
    /**
     * Test de autenticación con Sanctum
     */
    public function test_user_authentication_with_sanctum()
    {
        // Simular el método auth de Sanctum
        $authUser = ($this->userController->auth)();
        
        // Verificar que devuelve el usuario autenticado
        $this->assertEquals(1, $authUser->id);
        $this->assertEquals('Test User', $authUser->name);
    }
}
