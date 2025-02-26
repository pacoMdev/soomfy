<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nameImg', 'categoria_id'];


    /**
     * Define la relación "pertenece a" (belongsTo) con otra categoría.
     *
     * Esta relación indica que una categoría puede pertenecer a una categoría principal,
     * es decir, puede ser una subcategoría de otra categoría.
     *
     * Por ejemplo:
     * - Si esta categoría es "Laptops", entonces su categoría principal
     *   podría ser "Tecnología", y el campo 'categoria_id' almacenará el
     *   ID de la categoría "Tecnología".
     *
     * Este método se utiliza para acceder a la categoría principal de una subcategoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *         Una instancia de la clase BelongsTo que conecta esta categoría
     *         con su categoría principal.
     */
    public function categoria()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    /**
     * Define la relación "tiene muchas" (hasMany) con otras categorías.
     *
     * Esta relación indica que una categoría principal puede tener muchas subcategorías,
     * todas ellas asociadas a través del campo 'categoria_id'.
     *
     * Por ejemplo:
     * - Si esta categoría es "Tecnología", sus subcategorías podrían ser "Laptops",
     *   "Televisores", o "Móviles", y todas ellas tendrán el valor 'categoria_id=1'.
     *
     * Este método se utiliza para acceder a todas las subcategorías de una categoría principal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *         Una instancia de la clase HasMany que conecta esta categoría
     *         con todas sus subcategorías.
     */
    public function subcategorias()
    {
        return $this->hasMany(Category::class, 'categoria_id');
    }

    /**
     * Get the products for the category.
     */
    public function products()
    {
        // $table = 'categories';
        return $this->belongsToMany(Product::class,'category_product');
    }

}
