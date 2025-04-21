<?php

namespace App\Http\Controllers\Api;

use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreproductRequest;
use App\Http\Resources\ProductResource;

use App\Models\Product;
use App\Models\Message;
use App\Models\Transactions;
use App\Models\UserOpinion;
use App\Models\User;

use App\Mail\ConstructEmail;
use App\Models\ShippingAddressTransaction;
use Kreait\Firebase\Database\Transaction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class ProductControllerAdvance extends Controller
{

    /**
     * Summary of store
     * Guarda la info del product
     * @param \App\Http\Requests\StoreproductRequest $request
     * @return ProductResource
     */
    public function store(StoreProductRequest $request)
    {
        // Validar los datos
        $validatedData = $request->validated();

        // Crear el producto
        $product = new Product();
        $product->title = $validatedData['title'];
        $product->content = $validatedData['content'];
        $product->estado_id = $validatedData['estado_id'];
        $product->price = $validatedData['price'];
        $product->user_id = auth()->id();
        $product->save();

        // Guardar las categorías en la tabla intermedia
        if (isset($validatedData['categories']) && is_array($validatedData['categories'])) {
            $product->categories()->sync($validatedData['categories']);
        }

        // Manejar imágenes - Corregido el manejo de thumbnails
        if ($request->hasFile('thumbnails')) {
            foreach ($request->file('thumbnails') as $thumbnail) {
                $product->addMedia($thumbnail)  // Cambiado aquí
                ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }

        return new ProductResource($product);


    }
    public function show(Product $product)
    {
        $this->authorize('product-edit');
        $product->load('user','categories','estado', 'media');
        return new ProductResource($product);
}


    //NO edita imagen
    public function update(Product $product, StoreProductRequest $request)
    {
        $this->authorize('product-edit');

        if ($product->user_id !== auth()->id() && !auth()->user()->hasPermissionTo('product-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own products']);
        } else {
            $product->update($request->validated());

            if ($request->hasFile('thumbnails')) {
                // Recorrem totes les noves imatges
                foreach ($request->file('thumbnails') as $index => $image) {
                    // Obtenim l'UUID anterior (si existeix)
                    $previousId = $request->input("thumbnails_previous_id.$index");

                    // Obtenim l'ordre de la imatge
                    $order = $request->input("thumbnails_order.$index");

                    // Si tenim un UUID anterior, això significa que estem reemplaçant una imatge
                    if ($previousId) {
                        // Elimina la imatge antiga pel seu UUID
                        Media::where('uuid', $previousId)->where('model_id', $product->id)->delete();
                    }

                    // Afegim la nova imatge amb l'ordre especificat
                    $mediaItem = $product->addMedia($image)
                        ->withResponsiveImages()
                        ->toMediaCollection('thumbnails');

                    // Si volem guardar l'ordre, podem afegir custom properties
                    if ($order !== null) {
                        $mediaItem->setCustomProperty('order', $order); // Esto hace que se agregue la imagen en el slot correspondiente?
                        $mediaItem->save();
                    }
                }
            }




            $product->load('user','categories','estado', 'media');

            return new ProductResource($product);

        }
    }

    public function destroy(Product $product)
    {
        $this->authorize('product-delete');
        if ($product->user_id !== auth()->id() && !auth()->user()->hasPermissionTo('product-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only delete your own products']);
        } else {
            $product->delete();
            return response()->noContent();
        }
    }

    /**
     * obtiene todos los productos filtrados o no(por defecto)
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProducts()
    {
        $paginate = request('paginate', null);
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'title', 'created_at', 'price'])) {
            $orderColumn = 'created_at';
        }

        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $orderPrice = request('order_price', null);
        if (!in_array($orderPrice, ['asc', 'desc'])) {
            $orderPrice = 'desc';
        }

        // Obtenemos la latitud y longitud
        $latitude = request('search_latitude', null);
        $longitude = request('search_longitude', null);

        // Búsqueda global
        $searchGlobal = request('search_global', null);

        // Primero, obtenemos los IDs de productos vendidos
        $soldProductIds = ShippingAddressTransaction::where('status', 'finished')
            ->with('transaction.product')
            ->get()
            ->pluck('transaction.product.id')
            ->unique()
            ->values();

        // Muestra todos los productos con su categoria y foto - excluyendo los vendidos
        $products = Product::with('user','estado','categories', 'media')
            ->whereNotIn('id', $soldProductIds) // excluye productos vendidos desde el principio
            // Busqueda global
            ->when($searchGlobal = request('search_global'), function($query) use ($searchGlobal) {
                $query->where(function($q) use ($searchGlobal) {
                    $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchGlobal) . '%'])
                        ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($searchGlobal) . '%'])
                        ->orWhere('id', 'LIKE', '%' . $searchGlobal . '%')
                        ->orWhereHas('categories', function($categoryQuery) use ($searchGlobal) { // Changed from 'category' to 'categories'
                            $categoryQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchGlobal) . '%']);
                        })
                        ->orWhereHas('estado', function($estadoQuery) use ($searchGlobal) {
                            $estadoQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchGlobal) . '%']);
                        });
                });
            })

            // Se ejecutara si existe el parametro search_category
            ->when(request('search_category'), function($query) {
                $categories = explode(',', request('search_category')); // Split categories by commas
                $query->whereHas('categories', function($q) use ($categories) {
                    $q->whereIn('categories.name', $categories); // Use whereIn to match any of the categories
                });
            })
            // Filtrar por estado
            ->when(request('search_estado'), function($query) {
                $estados = explode(',', request('search_estado')); // Split states by commas
                $query->whereHas('estado', function($q) use ($estados) {
                    $q->whereIn('name', $estados); // Use whereIn to match any of the states
                });
            })
            // Preserve searchGlobal
            ->when($searchGlobal, function($query) use ($searchGlobal) {
                $query->where(function($q) use ($searchGlobal) {
                    $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchGlobal) . '%'])
                        ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($searchGlobal) . '%'])
                        ->orWhere('id', 'LIKE', '%' . $searchGlobal . '%')
                        ->orWhereHas('categories', function($categoryQuery) use ($searchGlobal) {
                            $categoryQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchGlobal) . '%']);
                        })
                        ->orWhereHas('estado', function($estadoQuery) use ($searchGlobal) {
                            $estadoQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchGlobal) . '%']);
                        });
                });
            })
            // Filtro por título
            ->when(request('search_title'), function($query) {
                $searchTitle = request('search_title');
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTitle) . '%']);
            })
            ->when(request('search_id'), function($query) {
                $searchTitle = request('search_id');
                $query->whereRaw('LOWER(id) LIKE ?', ['%' . strtolower($searchTitle) . '%']);
            })
            // Filtro por fecha
            ->when(request('search_date'), function($query) {
                $query->whereDate('created_at', request('search_date'));
            })
            // Filtro por precio min-max
            ->when(request('min_price') && request('max_price'), function($query) {
                $query->whereBetween('price', [ request('min_price'), request('max_price')]);
            })
            // Filtro por ubicacion
            ->when($latitude && $longitude, function ($query) use ($latitude, $longitude) {
                $radius = request('search_radius', 1000); // Por defecto, 5 km
                if ($radius == 0) {
                    // Ubicación exacta
                    $query->whereHas('user', function ($q) use ($latitude, $longitude) {
                        $q->whereRaw('users.latitude = ? AND users.longitude = ?', [$latitude, $longitude]);
                    });
                } else {
                    // Filtro por radio
                    $query->whereHas('user', function ($q) use ($latitude, $longitude, $radius) {
                        $q->whereRaw('ST_Distance_Sphere(point(users.longitude, users.latitude), point(?, ?)) < ?', [
                            $longitude, $latitude, $radius
                        ]);
                    });
                }
            })
            // Agregar múltiples órdenes condicionalmente
            // Ordenar por id
            ->when($orderColumn == 'id', function ($query) use ($orderDirection) {
                // Si específicamente queremos ordenar por ID
                $query->orderBy('id', $orderDirection);
            })
            // Ordenar por orden de creación
            ->when($orderColumn == 'created_at', function ($query) use ($orderDirection) {
                $query->orderBy('created_at', $orderDirection);
            })
            // Ordenar por titulo
            ->when($orderColumn == 'title', function ($query) use ($orderDirection) {
                // Si específicamente queremos ordenar por ID
                $query->orderBy('title', $orderDirection);
            })
            // Ordenar por categoria
            ->when($orderColumn == 'category_id', function ($query) use ($orderDirection) {
                // Si específicamente queremos ordenar por ID
                $query->orderBy('category_id', $orderDirection);
            })
            // Ordenar por precio si existe
            ->when($orderPrice, function ($query) use ($orderPrice) {
                $query->orderBy('price', $orderPrice);
            })
            ->when($orderColumn && $orderDirection, function ($query) use ($orderColumn, $orderDirection) {
                $query->orderBy($orderColumn, $orderDirection);
            })
            ->paginate($paginate);

        return ProductResource::collection($products);
    }


    public function getCategoryByProducts($id)
    {
        try {
            $products = Product::whereHas('categories', function($query) use ($id) {
                $query->where('categories.id', $id);
            })
            ->with(['estado', 'categories', 'media'])
            ->get();

            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEstadoList()
    {
        $estados = Estado::all();
        return response()->json(['data' => $estados]);

    }
    // Favoritos

    // Agregar a favoritos 
    public function gestorFavoritos($productId) {
        $user = auth()->user();
        if ($user->favoritos->contains($productId)) {
            $user->favoritos()->detach($productId);
            return response()->json([
                'message' => 'Producto eliminado de favoritos',
                'productoAgregado' => false
            ]);
        } else {
            $user->favoritos()->attach($productId);
            return response()->json([
                'message' => 'Producto agregado a favoritos',
                'productoAgregado' => true
            ]);
        }

    }
    


    // Función para mostrar los products favoritos
     public function getFavoriteProducts(){
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $favoritos = $user->favoritos;

            return ProductResource::collection($favoritos);
    }
    public function checkSelledProduct(Request $request)
    {
        $productId = $request->input('product_id');

        $selledInPerson = Transactions::where('product_id', $productId)
        ->where('delivery_type', 'in_person')
        ->get()
        ->count();
        if ($selledInPerson > 0){
            return response()->json([
                'isProcessed' => true,
                'isSelled' => true,
            ]);
        }

        $isSelled = ShippingAddressTransaction::whereIn('status', ['finished', 'pending'])
            ->whereHas('transaction.product', function ($query) use ($productId) {
                $query->where('id', $productId);
            })
            ->get();
        // dd($isSelled);        

        return response()->json([
            'isProcessed' => $isSelled->count() >= 1 ? true : false,
            'isSelled' => $isSelled->count() >=2 ? true : false,
        ]);

    }
}
