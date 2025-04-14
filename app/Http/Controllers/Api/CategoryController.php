<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Lista categorías con filtros y ordenamiento opcional.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Si se llama sin el parámetro paginated=true y con raw=true
        // delegamos al método getCategories para mantener la compatibilidad
        if (request('raw') && !request('paginated')) {
            return $this->getCategories();
        }

        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $categories = Category::
        when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%' . request('search_global') . '%');

                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);
        return CategoryResource::collection($categories);
    }

    /**
     * Obtiene todas las categorías con los medios asociados.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCategories()
    {
        $categories = Category::with('media')->get();
        return CategoryResource::collection($categories);
    }

    /**
     * Crea una nueva categoría.
     *
     * @param StoreCategoryRequest $request
     * @return CategoryResource
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')
                ->toMediaCollection('original_image');
        }

        return new CategoryResource($category);

    }

    /**
     * Muestra una categoría específica.
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        $this->authorize('category-edit');
        return new CategoryResource($category);
    }

    /**
     * Actualiza una categoría específica.
     *
     * @param Category $category
     * @param StoreCategoryRequest $request
     * @return CategoryResource
     */
    public function update(Category $category, StoreCategoryRequest $request)
    {
        $this->authorize('category-edit');
        $category->update($request->validated());

        if ($request->hasFile('image')) {
            // clearMediaCollection, borra lo que esté asociado a original_image
            $category->clearMediaCollection('original_image');
            // Luego agregar la nueva imagen
            $category->addMediaFromRequest('image')
                ->toMediaCollection('original_image');
        }

        return new CategoryResource($category);
    }

    /**
     * Elimina una categoría específica.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('category-delete');
        $category->delete();

        return response()->noContent();
    }

    /**
     * Devuelve una lista de todas las categorías.
     * Se utiliza para los selects
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getList()
    {
        return CategoryResource::collection(Category::all());
    }

}
