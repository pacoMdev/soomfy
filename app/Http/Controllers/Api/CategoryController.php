<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function getCategories()
    {
        $categories = Category::with('media', 'subcategories')->get();


        return response()->json($categories);


    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json($category, 201);
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
