<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserOpinion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\ProductResource;
use App\Models\Transactions;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsSuccessful;


class ProfileController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->load('media');
        \Log::info('Media cargado:', [
            'tiene_media' => $profile->getMedia('*')->count(),
            'media_collection' => $profile->getMedia('*')->toArray()
        ]);
        return new UserResource($profile);

    }
    public function getUserInfo($userId)
    {
        $profile = User::with('media')->findOrFail($userId);

    return new UserResource($profile);

    }
    /**
     * @throws ValidationException
     */
    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user();
        $profile->name = $request->name;
        $profile->surname1 = $request->surname1;
        $profile->surname2 = $request->surname2;
        $profile->password = $request->password;

        if ($profile->save()) {
            return $this->successResponse($profile, 'User updated');;
        }
        return response()->json(['status' => 403, 'success' => false]);
    }

    public function updateimg(Request $request)
    {
        // Almacenamos el usuario en una variable
        $user = auth()->user();

        // Comprobamos si recibe una imagen
        if($request->hasFile('image')) {
            // Si la recibe, limpiamos primero la que ya existe (si existiera)
            $user->clearMediaCollection('avatar');
            // Para despues asignar la imagen al campo 'original_image'
            $user->addMediaFromRequest('image')
                ->toMediaCollection('avatar');
        }

        return new UserResource($user);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load('roles');
        $avatar = '';
        if (count($user->media) > 0) {
            $avatar = $user->media[0]->original_url;
        }
        $user->avatar = $avatar;

        return $this->successResponse($user, 'User found');
    }

    public function getAllToSell($userId){
        $products = User::find($userId)->products;

        $soldProductIds = Transactions::pluck('product_id'); // IDs de productos vendidos/comprados
        $filteredProducts = $products->reject(function ($product) use ($soldProductIds) {
            return $soldProductIds->contains($product->id);
            
        });
        // dd($filteredProducts);

        return ProductResource::collection($filteredProducts);
    }
    public function getPurchase($userId){
        $purchase = User::find($userId)->purchase()
        ->with(['product', 'seller', 'buyer'])
        ->get();

        return $this->successResponse($purchase, 'Transaction found');
    }
    
    public function getSales($userId){
        $purchase = User::find($userId)->sales()
        ->with(['product', 'seller', 'buyer'])
        ->get();

        return $this->successResponse($purchase, 'Transaction found');
    }
    public function getValorations($userId){
        $reviews = UserOpinion::whereIn('product_id', function ($query) use ($userId) {
            $query->select('product_id')
                  ->from('transactions')
                  ->where('userSeller_id', $userId);
        })->with(['user', 'product'])->get();
        return $this->successResponse($reviews, 'Reviews found');
    }

    public function getUserByProductId($productId){
        $product = Product::find( $productId)->load([ 'user' ]);
        return new ProductResource($product);
    }

}
