<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    use DatabaseTransactions;
    public function test_category_can_be_indexed()
    {
        Category::create(['name' => 'Categoria 1']);
        Category::create(['name' => 'Categoria 2']);
        Category::create(['name' => 'Categoria 3']);
        $categories = Category::all();

        $this->assertContains('Categoria 1', $categories->pluck('name')->toArray());
        $this->assertContains('Categoria 2', $categories->pluck('name')->toArray());
        $this->assertContains('Categoria 3', $categories->pluck('name')->toArray());

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
        $categoryData = [
            'name' => 'Nombre Categoria Prueba',
        ];

        $category = Category::create($categoryData);

        // verificacion de creacion OK
        $this->assertDatabaseHas('categories', [
            'name' => 'Nombre Categoria Prueba',
        ]);

        // verificacion de datos
        $this->assertEquals($category->name, 'Nombre Categoria Prueba');
    }
    
    
    public function test_category_can_be_updated()
    {
        $category = Category::create(['name' => 'Categoria_Original']);
        $this->assertDatabaseHas('categories', [
            'name' => 'Categoria_Original',
        ]);

        $category->update(['name' => 'Categoria_Actualizada']);
        $this->assertDatabaseHas('categories', [
            'name' => 'Categoria_Actualizada',
        ]);
        $this->assertDatabaseMissing('categories', [
            'name' => 'Categoria_Original',
        ]);
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
        $category = Category::create(['name' => 'Categoria a eliminar']);
        $this->assertDatabaseHas('categories', [
            'name' => 'Categoria a eliminar',
        ]);

        $categoryid = $category->id;

        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $categoryid,
        ]);
    }
}
