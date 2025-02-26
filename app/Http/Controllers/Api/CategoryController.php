<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Obtener la columna y la dirección de ordenamiento con valores por defecto
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        // Obtener las categorías principales y sus subcategorías según los filtros
        $categories = Category::
        with('subcategorias') // AGREGADO NUEVO - Cargar relaciones con subcategorías
        ->whereNull('categoria_id') // AGREGADO NUEVO - Filtrar solo categorías principales
        ->when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%'.request('search_title').'%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%'.request('search_global').'%');
                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

        // Devolver las categorías en formato de recurso con sus subcategorías
        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        // Verifica si el usuario tiene permiso para crear categorías
        $this->authorize('category-create');

        // Obtiene y valida los datos enviados mediante el StoreCategoryRequest
        $validatedData = $request->validated();

        // Validar que si se especifica una subcategoría (categoria_id),
        // esta categoría debe pertenecer a una categoría principal
        if (isset($validatedData['categoria_id'])) {
            // Busca la categoría principal según el ID proporcionado en categoria_id
            $parentCategory = Category::find($validatedData['categoria_id']);

            // Verifica que la categoría principal exista y no sea en sí misma una subcategoría
            if (!$parentCategory || $parentCategory->categoria_id !== null) {
                // Devuelve un error si no es válida
                return response()->json(['error' => 'La subcategoría debe pertenecer a una categoría principal válida.'], 422);
            }
        }

        // Crea la nueva categoría o subcategoría con los datos validados
        $category = Category::create($validatedData);

        // Devuelve la categoría recién creada como un recurso JSON
        return new CategoryResource($category);
    }


    public function show(Category $category)
    {
        $this->authorize('category-edit');
        return new CategoryResource($category);
    }

    public function update(Category $category, StoreCategoryRequest $request)
    {
        $this->authorize('category-edit');
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category) {
        $this->authorize('category-delete');
        $category->delete();

        return response()->noContent();
    }

    public function getList()
    {
        return CategoryResource::collection(Category::all());
    }
    public function getCategory(){
        $category = Category::all();
        return response()->json(['code' => 200, 'status'=>'done', 'category' => $category]);
    }
}
