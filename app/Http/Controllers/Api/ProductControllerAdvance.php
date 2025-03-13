<?php

namespace App\Http\Controllers\Api;

use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreproductRequest;
use App\Http\Resources\ProductResource;

use App\Models\Category;
use App\Models\Product;
use App\Models\Message;
use App\Models\Product_image;
use App\Models\Transactions;
use App\Models\User;

use App\Mail\ConstructEmail;


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
        $product->category_id = $validatedData['category_id'];
        $product->user_id = auth()->id();

        $product->save();

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
        if ($product->user_id !== auth()->user()->id && !auth()->user()->hasPermissionTo('product-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own products']);
        } else {
            $product->load('user','category','estado', 'media');
            return new ProductResource($product);
        }
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
                $mediaItems = [];

                // Mantener imágenes existentes que no se están reemplazando
                $existingMedia = $product->getMedia('images');
                foreach ($existingMedia as $index => $media) {
                    if (!isset($request->file('thumbnails')[$index])) {
                        $mediaItems[] = $media;
                    }
                }

                // Procesar nuevas imágenes
                foreach ($request->file('thumbnails') as $index => $thumbnail) {
                    $mediaItems[] = $product
                        ->addMedia($thumbnail)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }

                // Sincronizar con la colección (eliminará las imágenes antiguas)
                $product->syncMedia($mediaItems, 'images');

            }

                $product->load('user','category','estado', 'media');

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

    public function getProducts()
    {

        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'title', 'created_at', 'price'])) {
            $orderColumn = 'created_at';
        }

        $orderDate = request('order_direction', 'desc');
        if (!in_array($orderDate, ['asc', 'desc'])) {
            $orderDate = 'desc';
        }

        $orderPrice = request('order_price', null);
        if (!in_array($orderPrice, ['asc', 'desc'])) {
            $orderPrice = 'desc';
        }

        // Obtenemos la latitud y longitud
        $latitude = request('search_latitude', null);
        $longitude = request('search_longitude', null);


        // Muestra todos los productos con su categoria y foto
        $products = Product::with('user','estado','category', 'media')
            // Se ejecutara si existe el parametro search_category
            ->when(request('search_category'), function($query) {
                // Busca los prooductos que tengan una relacion con la tabla categorias
                $query->whereHas('category', function($q) {
                    // Si el nombre de la categoria pasada por parametro coincide con alguna relacion producto - categoria
                    // Devolvera solo los productos con esa categoria
                    $q->whereRaw('LOWER(name) = ?', [strtolower(request('search_category'))]
                    );
                });
            })
            // Filtrar por estado
            ->when(request('search_estado'), function($query) {
                // Busca los prooductos que tengan una relacion con la tabla estados
                $query->whereHas('estado', function($q) {
                    // Si el nombre de la estado pasada por parametro coincide con alguna relacion producto - estado
                    // Devolvera solo los productos con ese estado
                    $q->whereRaw('LOWER(name) = ?', [strtolower(request('search_estado'))]
                    );
                });
            })
            // Filtro por título
            ->when(request('search_title'), function($query) {
                $searchTitle = request('search_title');
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTitle) . '%']);
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
            // Ordenar por precio si existe
            ->when($orderPrice, function ($query) use ($orderPrice) {
                $query->orderBy('price', $orderPrice);
            })
            ->when($orderColumn && $orderDate, function ($query) use ($orderColumn, $orderDate) {
                $query->orderBy($orderColumn, $orderDate);
            })
            ->get();
            // excluye los productos ya vendidos de transactions
            $soldProductIds = Transactions::pluck('product_id');
            $filteredProducts = $products->reject(function ($product) use ($soldProductIds) {
                return $soldProductIds->contains($product->id);
            });

        return ProductResource::collection($filteredProducts);
    }


    public function getCategoryByProducts($id)
    {
        $products = Product::whereRelation('products', 'id', '=', $id)->paginate();

        return ProductResource::collection($products);
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

    public function sellProduct(Request $request){
        $userSeller = auth()->user();
        $userBuyer = User::findOrFail($request -> userBuyer_id);
        $product = Product::findOrFail($request -> product_id);
        
        if ($request -> finalPrice == 0){
            $initialPrice = $product-> price;
            $finalPrice = $product -> price;
            $isRegated = false;
        }else{
            $initialPrice = $product -> price;
            $finalPrice = $request -> finalPrice;
            $isRegated = true;
        }

        $transaction = new Transactions();
        $transaction -> userSeller_id = $userSeller -> id;
        $transaction -> userBuyer_id = $userBuyer -> id;
        $transaction -> product_id = $product -> id;
        $transaction -> initialPrice = $initialPrice;
        $transaction -> finalPrice = $finalPrice;
        $transaction -> isToSend = $request -> isToSend;
        $transaction -> isRegated = $isRegated;

        $transaction -> save();

        // EMAIL VENTA ---------------------------------------------------------------------------------------
        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Seller',
            'to_email'      => $userSeller -> email,
            'to_name'       => $userSeller -> name,
            'subject'       => 'Hey acabas de vender un producto',
            'view'          => 'emails.sellProduct',
            'finalPrice'    => $finalPrice,
            'userSeller'    => $userSeller,
            'userBuyer'     => $userBuyer,
            'product'       => $product,
            'saleDate'      => $transaction -> created_at,
            'url'    => getenv('APP_URL') . '/products/detalle/' . $product -> id,
        ];
        // Manda el email
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);

        // EMAIL OPINION ---------------------------------------------------------------------------------------
        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Seller',
            'to_email'      => $userBuyer -> email,
            'to_name'       => $userBuyer -> name,
            'subject'       => 'Hey acabas de vender un producto',
            'view'          => 'emails.valoration',
            'finalPrice'    => $finalPrice,
            'userSeller'    => $userSeller,
            'userBuyer'     => $userBuyer,
            'product'       => $product,
            'saleDate'      => $transaction -> created_at,
            'url'    => getenv('APP_URL') . '/products/detalle/' . $product -> id,
        ];
        // Manda el email
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);


        return response() -> json(['status' => 200, ' succsss' => true, 'seller' => $userSeller, 'buyer' => $userBuyer, 'product' =>$transaction]);
    }

    public function getUsersConversations($productId){
    $productOwnerId = Product::find( $productId)->load([ 'users' ]);
    // dd($productOwnerId['users'][0]['id']);
    
    $userIds = Message::where('product_id', $productId)
        ->pluck('userRemitent_id')
        ->unique()
        ->reject(fn($id) => $id == $productOwnerId['users'][0]['id']);
    $allUsers = User::whereIn('id', $userIds)->with('media')->get();
    // dd($allUsers);
    return $allUsers;
    }
}
