<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SubcategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)
    {
        // Aseguramos que sea una subcategoría estableciendo category_id como requerido
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        $subcategory = Category::create($validated);
        return response()->json($subcategory, 201);
    }

}
