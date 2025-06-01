<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    
    public function test_category_can_be_created()
    {
        $categoryData = [
            'name' => 'Nombre Categoria Prueba',
        ];

        $category = Category::create($categoryData);

        $this->assertDatabaseHas('categories', [
            'name' => 'Nombre Categoria Prueba',
        ]);

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
