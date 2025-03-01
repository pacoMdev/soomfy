<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreproductRequest;
use App\Http\Resources\ProductResource;

use App\Models\Category;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Transactions;
use App\Models\User;

use App\Mail\ConstructEmail;


class ProductControllerAdvance extends Controller
{
    public function index()
    {

    }


    /**
     * Summary of store
     * Guarda la info del product
     * @param \App\Http\Requests\StoreproductRequest $request
     * @return ProductResource
     */
    public function store(StoreProductRequest $request)
    {
        dd($request);


        // dd($request);
        // $this->authorize('product-create');
        // $validatedData = $request->validated();
        // $validatedData['user_id'] = auth()->id();
        
        // dd($validatedData);
        
        $product = new Product();
        $product -> title = $request["title"] ?? '';
        $product -> content = $request["content"] ?? '';
        $product -> estado = $request["estado"] ?? '';
        $product -> price = $request["price"] ?? 0;

        $product->save();

        // Acocia las categorias del producto con las extraidas de $request
        $categories = explode(",", $request->categories);
        $category = Category::findMany($categories);
        $product->categories()->sync($category);
        
        // Asocia las imagenes al posts
        // falta modificar para que sean el array de imagenes(thrumbnail)
        if ($request->hasFile('thumbnails')) {
            foreach($request->thumbnails as $thumbnail){
                $product->addMediaFromRequest($thumbnail)->preservingOriginal()->toMediaCollection('images');
            }
        }

        dd($product);

        // Asociacion de producto user

        // $userProduct = new userProduct();
        // $userProduct -> user_id = $user_id;
        // $userProduct -> product_id = $product->id;

        // return new ProductResource($product);
    }

    public function show(Product $product)
    {
        $this->authorize('product-edit');
        if ($product->user_id !== auth()->user()->id && !auth()->user()->hasPermissionTo('product-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own products']);
        } else {
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

            $category = Category::findMany($request->categories);
            $product->categories()->sync($category);

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
        $products = Product::with('category')->with('media')->latest()->paginate();
        return ProductResource::collection($products);

    }

    public function getCategoryByProducts($id)
    {
        $products = Product::whereRelation('products', 'id', '=', $id)->paginate();

        return ProductResource::collection($products);
    }

    public function getProduct($id)
    {
        return Product::with('products', 'users', 'media')->findOrFail($id);
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
        $userSeller = User::findOrFail($request -> userSeller_id);
        $userBuyer = User::findOrFail($request -> userBuyer_id);
        
        if ($request -> initialPrice != $request -> finalPrice){
            $initialPrice = $request -> initialPrice;
            $finalPrice = $request -> finalPrice;
            $isRegated = true;
        }else{
            $initialPrice = $request -> initialPrice;
            $finalPrice = $request -> finalPrice;
            $isRegated = false;
        }

        $transaction = new Transactions();
        $transaction -> userSeller_id = $userSeller -> id;
        $transaction -> userBuyer_id = $userBuyer -> id;
        $transaction -> product_id = $request -> product_id;
        $transaction -> initialPrice = $initialPrice;
        $transaction -> finalPrice = $finalPrice;
        $transaction -> isToSend = $request -> isToSend;
        $transaction -> isRegated = $isRegated;

        $transaction -> save();

        $data = [
            'from_email' => 'soomfy@gmail.com',
            'from_name' => 'Soomfy',
            'to_email' => $userSeller['email'],
            'to_name' => $userSeller['name'],
            'subject' => 'Hey acabas de vender un producto',
            'view' => 'emails.welcome',
            'finalPrice' => $finalPrice,
            'userSeller' => $userSeller,
            'userBuyer' => $userBuyer,
        ];
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);


        return response() -> json(['status' => 200, ' succsss' => true, 'seller' => $userSeller, 'buyer' => $userBuyer, 'product' =>$transaction]);
    }
    function getUserProducts(){

        $user = auth()->user();
        // dd($user);
        $products = Product::with('user_product')->latest()->paginate();

        return $products;
    }
}
