<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostsResource;
use App\Models\Posts;
use App\Models\User;
use App\Models\Transactions;
use App\Http\Controllers\Controller;
use App\Mail\ConstructEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\helpers;


class PostsController extends Controller
{
    /**
     * Summary of index
     * Muestra todos los posts
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authors = Posts::all();
        return response()->json(['status' => 405, 'success' => true, 'data' => $authors]);
    }


    /**
     * Summary of store
     * Almacena el post en base de datos
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Crea el post para guardar en base de datos
        $post = new Posts();
        $post -> title = $request -> title;
        $post -> description = $request -> description;
        $post -> price = $request -> price;
        $post -> estado = $request -> estado;
        $post -> latitude = $request -> latitude;
        $post -> longitude = $request -> longitude;
        $post -> toSend = $request -> toSend;
        $post -> isDeleted = $request -> isDeleted;
        $post -> isBoost = $request -> isBoost;
        $post -> dimensionX = $request -> dimensionX;
        $post -> dimensionY = $request -> dimensionY;
        $post -> marca = $request -> marca;
        $post -> color = $request -> color;
        $post -> category_id = $request -> category_id;

        // Guardar en la base de datos
        $post -> save();

        return response()->json(data: ['status' => 200, 'success' => true, 'mensaje' => 'Post guardado', 'post' => $post]);
    }

    /**
     * Summary of show
     * Muestra el post con x id
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // busca por id
        $post = Posts::findOrFail($id);

        return response()->json(['status' => 200, 'success' => true, 'data' => $post]);
    }
    /**
     * Summary of update
     * Actualiza el post
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $post = Posts::findOrFail($id);
        
        // Crea el post para guardar en base de datos
        $post -> title = $request -> title ?? $post -> title;
        $post -> description = $request -> description ?? $post -> description;
        $post -> price = $request -> price ?? $post -> price;
        $post -> estado = $request -> estado ?? $post -> estado;
        $post -> latitude = $request -> latitude ?? $post -> latitude;
        $post -> longitude = $request -> longitude ?? $post -> longitude;
        $post -> toSend = $request -> toSend ?? $post -> toSend;
        $post -> isDeleted = $request -> isDeleted ?? $post -> isDeleted;
        $post -> isBoost = $request -> isBoost ?? $post -> isBoost;
        $post -> dimensionX = $request -> dimensionX ?? $post -> dimensionX;
        $post -> dimensionY = $request -> dimensionY ?? $post -> dimensionY;
        $post -> marca = $request -> marca ?? $post -> marca;
        $post -> color = $request -> color ?? $post -> color;
        $post -> category_id = $request -> category_id ?? $post -> category_id;

        // Guardar en la base de datos
        $post->save();
        
        return response()->json(['status' => 200, 'success' => true, 'mensaje' => 'Post actualizado', 'post' => $post]);
    }

    /**
     * Summary of delete
     * Elimina el post
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $post = Posts::findOrFail($id);
        $post->delete();
        return response()->json(['status' => 200, 'success' => true, 'mensaje' => 'Post eliminado']);
    }


    /**
     * Summary of getNearbyPosts
     * Obtiene los posts en radio base 10km, se puede ajustar
     * 
     * Se puede modificar poniendo la direccion y CP luego calcular latitud | longitud
     * 
     * @param mixed $latitude
     * @param mixed $longitude
     * @param mixed $radius
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getNearbyPost($latitude, $longitude, $radius = 10){
    $posts = Posts::selectRaw("
            id, title, description, price, estado, category_id,
            ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<', $radius)  // Radio en km (10 km por defecto)
        ->orderBy('distance')
        ->get();

        return response()->json(['status' => 200, 'success' => true, 'posts' => $posts]);
    }
    
    public function sellPost(Request $request){
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
        $transaction -> post_id = $request -> post_id;
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
        dd($data_email);


        return response() -> json(['status' => 200, ' succsss' => true, 'seller' => $userSeller, 'buyer' => $userBuyer, 'post' =>$transaction]);
    }
}
