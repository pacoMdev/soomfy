<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'categoria_id' => // Campo que representa la relación con una categoría superior (opcional).
                'nullable|exists:categories,id', // Permite valor nulo (es opcional) o debe existir en la tabla 'categories' en la columna 'id'.

        ];
    }
}
