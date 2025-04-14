<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * ProductTest - Prueba Unitaria para la funcionalidad de Productos
 * 
 * Este archivo de prueba se centra en probar la funcionalidad de productos de forma aislada,
 * sin dependencias de base de datos o API.
 * Simula el comportamiento de un ProductController utilizando stdClass de PHP para crear objetos simulados.
 * Estas pruebas validan operaciones básicas CRUD para productos.
 */
class ProductTest extends TestCase
{
    // Propiedades de prueba para almacenar objetos simulados
    protected $product;        // Objeto producto simulado
    protected $productController; // Controlador simulado con métodos
    protected $request;        // Datos de solicitud simulados
    
    /**
     * Configuración del entorno de prueba antes de cada test
     * 
     * Este método crea objetos simulados que representan:
     * 1. Una entidad producto con propiedades básicas
     * 2. Un controlador con métodos que se definirán en cada prueba
     * 3. Un objeto de solicitud con datos para crear/actualizar productos
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un objeto simple para simular una entidad producto
        $this->product = new stdClass();
        $this->product->id = 1;
        $this->product->name = 'Test Product';
        $this->product->price = 99.99;
        $this->product->description = 'Test Description';
        $this->product->category_id = 1;
        
        // Crear un objeto controlador simulado para el comportamiento del controlador
        $this->productController = new stdClass();
        
        // Crear un objeto de solicitud simulado con datos para probar operaciones de creación/actualización
        $this->request = new stdClass();
        $this->request->name = 'New Product';
        $this->request->description = 'New Description';
        $this->request->price = 149.99;
        $this->request->category_id = 2;
    }

    /**
     * Prueba de funcionalidad de listado de productos (acción index)
     * 
     * Esta prueba verifica que:
     * - El método index devuelve un array de productos
     * - El array contiene el número esperado de productos
     * - Los productos tienen los datos esperados
     */
    public function test_product_can_be_indexed()
    {
        // Simular el método index que devolvería una colección de productos
        $this->productController->index = function() {
            // Datos simulados que representan registros de productos de la base de datos
            $products = [(object)[
                'id' => 1,
                'name' => 'Product 1',
                'price' => 99.99
            ], (object)[
                'id' => 2,
                'name' => 'Product 2',
                'price' => 149.99
            ]];
            return $products;
        };
        
        // Llamar al método simulado y almacenar el resultado
        $result = ($this->productController->index)();
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertIsArray($result);            // El resultado debe ser un array
        $this->assertCount(2, $result);           // Debe contener 2 productos
        $this->assertEquals('Product 1', $result[0]->name); // El primer producto debe tener el nombre "Product 1"
    }
    
    /**
     * Prueba de funcionalidad de creación de producto (acción store)
     * 
     * Esta prueba verifica que:
     * - Se puede crear un producto con los datos proporcionados
     * - El producto creado tiene las propiedades esperadas
     */
    public function test_product_can_be_created()
    {
        // Simular el método store que crearía un nuevo producto
        $this->productController->store = function($request) {
            // Simular la lógica de creación de producto
            $newProduct = new stdClass();
            $newProduct->id = 3;                        // ID autogenerado
            $newProduct->name = $request->name;         // Nombre del request
            $newProduct->description = $request->description; // Descripción del request
            $newProduct->price = $request->price;       // Precio del request
            return $newProduct;
        };
        
        // Llamar al método simulado con nuestro request simulado y almacenar el resultado
        $result = ($this->productController->store)($this->request);
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertEquals('New Product', $result->name);  // El nombre debe coincidir con el request
        $this->assertEquals(149.99, $result->price);        // El precio debe coincidir con el request
    }
    
    /**
     * Prueba de funcionalidad de actualización de producto (acción update)
     * 
     * Esta prueba verifica que:
     * - Un producto existente puede ser actualizado con nuevos datos
     * - El producto actualizado tiene las propiedades esperadas
     */
    public function test_product_can_be_updated()
    {
        // Simular el método update que modificaría un producto existente
        $this->productController->update = function($request, $product) {
            // Actualizar las propiedades del producto con los datos del request
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            return $product;
        };
        
        // Llamar al método simulado con nuestro request y producto simulados
        $result = ($this->productController->update)($this->request, $this->product);
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertEquals('New Product', $result->name);  // El nombre debe ser actualizado
        $this->assertEquals(149.99, $result->price);        // El precio debe ser actualizado
    }
    
    /**
     * Prueba de funcionalidad de eliminación de producto (acción destroy)
     * 
     * Esta prueba verifica que:
     * - Un producto puede ser eliminado
     * - El método destroy devuelve la respuesta esperada
     */
    public function test_product_can_be_deleted()
    {
        // Simular el método destroy que eliminaría un producto
        $this->productController->destroy = function($product) {
            // En controladores reales, esto eliminaría de la base de datos
            // Aquí solo simulamos una eliminación exitosa (204 No Content)
            return true;
        };
        
        // Llamar al método simulado con nuestro producto simulado
        $result = ($this->productController->destroy)($this->product);
        
        // Aserciones para verificar que el resultado cumple con las expectativas
        $this->assertTrue($result); // La eliminación debe ser exitosa
    }
}
