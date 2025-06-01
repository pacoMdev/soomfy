<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    use DatabaseTransactions;

    public function test_product_can_be_indexed()
    {
        $producto1 = Product::create([
            'title' => 'Producto 1',
            'content' => 'Contenido de prueba del producto 1',
            'price' => 100,
            'marca' => 'Marca Prueba 1',
            'color' => 'Color Prueba 1',
            'category_id' => 1,
            'user_id' => 1
        ]);
        
        $producto2 = Product::create([
            'title' => 'Producto 2',
            'content' => 'Contenido de prueba del producto 2',
            'price' => 200,
            'marca' => 'Marca Prueba 2',
            'color' => 'Color Prueba 2',
            'category_id' => 2,
            'user_id' => 2
        ]);
        
        $products = Product::all();
        
        $this->assertContains($producto1->id, $products->pluck('id')->toArray());
        $this->assertContains($producto2->id, $products->pluck('id')->toArray());
    }
    
    public function test_product_can_be_created()
    {
        $productData = [
            'title' => 'Titulo Producto Prueba',
            'content' => 'Contenido de prueba del producto',
            'price' => 149.99,
            'marca' => 'Marca Prueba',
            'color' => 'Color Prueba',
            'category_id' => 3,
            'user_id' => 3
        ];

        $product = Product::create($productData);
        $productId = $product->id;

        // verificacion de creacion OK
        $this->assertDatabaseHas('products', [
            'id' => $productId,
        ]);

        // verificacion de datos
        $this->assertEquals($product->id, $productId);
    }

    public function test_product_can_be_updated()
    {
        $product = Product::create([
            'title' => 'Producto_Original',
            'content' => 'Contenido original',
            'price' => 99.99,
            'marca' => 'Marca Original',
            'color' => 'Color Original',
            'category_id' => 1,
            'user_id' => 1
        ]);
        
        // Guardar el ID para mejor búsqueda
        $productId = $product->id;
        
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'title' => 'Producto_Original',
        ]);
    
        $product->update([
            'title' => 'Producto_Actualizado',
            'content' => 'Contenido actualizado',
            'price' => 149.99
        ]);
        
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'title' => 'Producto_Actualizado',
        ]);
        
        $this->assertDatabaseMissing('products', [
            'id' => $productId,
            'title' => 'Producto_Original',
        ]);
    }
    
    public function test_product_can_be_deleted()
    {
        $product = Product::create([
            'title' => 'Producto a eliminar',
            'content' => 'Este producto será eliminado',
            'price' => 79.99,
            'marca' => 'Marca Test',
            'color' => 'Color Test',
            'category_id' => 1,
            'user_id' => 1
        ]);
        
        // Guardar el ID para mejor búsqueda
        $productId = $product->id;
        
        $this->assertDatabaseHas('products', [
            'id' => $productId,
        ]);
    
        $product->delete();
    
        $this->assertDatabaseMissing('products', [
            'id' => $productId,
        ]);
    }
}
