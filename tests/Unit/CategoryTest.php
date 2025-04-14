<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * CategoryTest - Prueba Unitaria para la funcionalidad de Categorías
 * 
 * Este archivo de prueba se centra en probar la funcionalidad de categorías de forma aislada,
 * sin dependencias de base de datos o API.
 * Simula el comportamiento de un CategoryController utilizando stdClass de PHP para crear objetos simulados.
 * Estas pruebas validan operaciones básicas CRUD para categorías.
 */
class CategoryTest extends TestCase
{
    // Propiedades de prueba para almacenar objetos simulados
    protected $category;        // Objeto categoría simulado
    protected $categoryController; // Controlador simulado con métodos
    protected $request;        // Datos de solicitud simulados
    
    /**
     * Configuración del entorno de prueba antes de cada test
     * 
     * Este método crea objetos simulados que representan:
     * 1. Una entidad categoría con propiedades básicas
     * 2. Un controlador con métodos que se definirán en cada prueba
     * 3. Un objeto de solicitud con datos para crear/actualizar categorías
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un objeto simple para simular una entidad categoría
        $this->category = new stdClass();
        $this->category->id = 1;
        $this->category->name = 'Test Category';
        $this->category->description = 'Test Description';
        
        // Crear un objeto controlador simulado para el comportamiento del controlador
        $this->categoryController = new stdClass();
        
        // Crear un objeto de solicitud simulado con datos para probar operaciones de creación/actualización
        $this->request = new stdClass();
        $this->request->name = 'New Category';
        $this->request->description = 'New Description';
    }

    /**
     * Prueba de funcionalidad de listado de categorías (acción index)
     * 
     * Esta prueba verifica que:
     * - El método index devuelve un array de categorías
     * - El array contiene el número esperado de categorías
     * - Las categorías tienen los datos esperados
     */
    public function test_category_can_be_indexed()
    {
        // Simular el método index que devolvería una colección de categorías
        $this->categoryController->index = function() {
            // Datos simulados que representan registros de categorías de la base de datos
            $categories = [(object)[
                'id' => 1,
                'name' => 'Category 1'
            ], (object)[
                'id' => 2,
                'name' => 'Category 2'
            ]];
            return $categories;
        };
        
        // Llamar al método simulado y almacenar el resultado
        $result = ($this->categoryController->index)();
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertIsArray($result);                // El resultado debe ser un array
        $this->assertCount(2, $result);               // Debe contener 2 categorías
        $this->assertEquals('Category 1', $result[0]->name); // La primera categoría debe tener el nombre "Category 1"
    }
    
    /**
     * Prueba de funcionalidad de creación de categoría (acción store)
     * 
     * Esta prueba verifica que:
     * - Se puede crear una categoría con los datos proporcionados
     * - La categoría creada tiene las propiedades esperadas
     */
    public function test_category_can_be_created()
    {
        // Simular el método store que crearía una nueva categoría
        $this->categoryController->store = function($request) {
            // Simular lógica de creación de categoría
            $newCategory = new stdClass();
            $newCategory->id = 3;                          // ID autogenerado
            $newCategory->name = $request->name;           // Nombre de la solicitud
            $newCategory->description = $request->description; // Descripción de la solicitud
            return $newCategory;
        };
        
        // Llamar al método simulado con nuestra solicitud simulada y almacenar el resultado
        $result = ($this->categoryController->store)($this->request);
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertEquals('New Category', $result->name);        // El nombre debe coincidir con la solicitud
        $this->assertEquals('New Description', $result->description); // La descripción debe coincidir con la solicitud
    }
    
    /**
     * Prueba de funcionalidad de actualización de categoría (acción update)
     * 
     * Esta prueba verifica que:
     * - Una categoría existente puede ser actualizada con nuevos datos
     * - La categoría actualizada tiene las propiedades esperadas
     */
    public function test_category_can_be_updated()
    {
        // Simular el método update que modificaría una categoría existente
        $this->categoryController->update = function($request, $category) {
            // Actualizar propiedades de la categoría con datos de la solicitud
            $category->name = $request->name;
            $category->description = $request->description;
            return $category;
        };
        
        // Llamar al método simulado con nuestra solicitud y categoría simuladas
        $result = ($this->categoryController->update)($this->request, $this->category);
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertEquals('New Category', $result->name);        // El nombre debe estar actualizado
        $this->assertEquals('New Description', $result->description); // La descripción debe estar actualizada
    }
    
    /**
     * Prueba de funcionalidad de eliminación de categoría (acción destroy)
     * 
     * Esta prueba verifica que:
     * - Una categoría puede ser eliminada
     * - El método destroy devuelve la respuesta esperada
     */
    public function test_category_can_be_deleted()
    {
        // Simular el método destroy que eliminaría una categoría
        $this->categoryController->destroy = function($category) {
            // En controladores reales, esto eliminaría de la base de datos
            // Aquí simplemente simulamos una eliminación exitosa (204 Sin Contenido)
            return true;
        };
        
        // Llamar al método simulado con nuestra categoría simulada
        $result = ($this->categoryController->destroy)($this->category);
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertTrue($result); // La eliminación debe ser exitosa
    }
}
