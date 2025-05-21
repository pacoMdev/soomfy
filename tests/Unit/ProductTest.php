<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;  // This will reset the database after each test

    /**
     * Prueba de funcionalidad de listado de productos
     * 
     * Esta prueba verifica que:
     * - Se pueden obtener varios productos de la base de datos
     * - El array contiene el número esperado de productos
     */
    public function test_product_can_be_indexed()
    {
        // Crear productos en la base de datos para la prueba
        $product = new Product();
        $product->name = 'Test Product';
        $product->description = 'Test Description';
        $product->price = 99.99;
        $product->save();
        
        $product1 = new Product();
        $product1->name = 'Product 1';
        $product1->description = 'Description 1';
        $product1->price = 99.99;
        $product1->save();
        
        $product2 = new Product();
        $product2->name = 'Product 2';
        $product2->description = 'Description 2';
        $product2->price = 149.99;
        $product2->save();
        
        // Obtener todos los productos de la base de datos
        $products = Product::all()->toArray();
        
        // Aserciones para verificar que los productos se recuperaron correctamente
        $this->assertIsNotArray($products);
        $this->assertCount(3, $products);
        $this->assertEquals('Test Product', $products[0]['name']);
        $this->assertEquals('Product 1', $products[1]['name']);
        $this->assertEquals('Product 2', $products[2]['name']);
    }
    
    /**
     * Prueba de funcionalidad de creación de producto
     * 
     * Esta prueba verifica que:
     * - Se puede crear un producto en la base de datos
     * - El producto creado tiene las propiedades esperadas
     */
    public function test_product_can_be_created()
    {
        // Crear un nuevo producto
        $product = new Product();
        $product->name = "iPhone";
        $product->description = "Smartphone de última generación";
        $product->price = 1499.99;
        
        // Guardar en la base de datos
        $saved = $product->save();
        
        // Verificar que se guardó correctamente
        $this->assertTrue($saved);
        
        // Recuperar el producto de la base de datos para verificar
        $savedProduct = Product::find($product->id);
        
        // Verificar que los datos se guardaron correctamente
        $this->assertEquals("iPhone", $savedProduct->name);
        $this->assertEquals("Smartphone de última generación", $savedProduct->description);
        $this->assertEquals(1499.99, $savedProduct->price);
    }
    
    /**
     * Prueba de funcionalidad de actualización de producto
     * 
     * Esta prueba verifica que:
     * - Un producto existente puede ser actualizado con nuevos datos
     * - El producto actualizado tiene las propiedades esperadas
     */
    public function test_product_can_be_updated()
    {
        // Crear un producto para actualizar
        $product = new Product();
        $product->name = "Original Product";
        $product->description = "Original Description";
        $product->price = 99.99;
        $product->save();
        
        // Actualizar el producto
        $product->name = "Updated Product";
        $product->description = "Updated Description";
        $product->price = 199.99;
        $product->save();
        
        // Recuperar el producto actualizado de la base de datos
        $updatedProduct = Product::find($product->id);
        
        // Aserciones para verificar que el producto fue actualizado correctamente
        $this->assertEquals("Updated Product", $updatedProduct->name);
        $this->assertEquals("Updated Description", $updatedProduct->description);
        $this->assertEquals(199.99, $updatedProduct->price);
    }
    
    /**
     * Prueba de funcionalidad de eliminación de producto
     * 
     * Esta prueba verifica que:
     * - Un producto puede ser eliminado de la base de datos
     */
    public function test_product_can_be_deleted()
    {
        // Crear un producto para eliminar
        $product = new Product();
        $product->name = "Product to Delete";
        $product->description = "This will be deleted";
        $product->price = 49.99;
        $product->save();
        
        // Guardar el ID para verificar después
        $productId = $product->id;
        
        // Eliminar el producto
        $deleted = $product->delete();
        
        // Verificar que la eliminación fue exitosa
        $this->assertTrue($deleted);
        
        // Intentar recuperar el producto eliminado
        $deletedProduct = Product::find($productId);
        
        // Verificar que el producto ya no existe
        $this->assertNull($deletedProduct);
    }
}
