<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $users = User::
        when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%'.request('search_title').'%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%'.request('search_global').'%');

                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $role = Role::find($request->role_id);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->surname1 = $request->surname1;
        $user->surname2 = $request->surname2;

        $user->password = Hash::make($request->password);

        if ($user->save()) {
            if ($role) {
                $user->assignRole($role);
            }
            return new UserResource($user);
        }
    }

    public function getUserProducts($id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        return response()->json($user->products); // Retorna los products del usuario
    }
    /**
     * Summary of getNearbyProducts
     * Obtiene los products en radio base 10km, se puede ajustar
     * 
     * Se puede modificar poniendo la direccion y CP luego calcular latitud | longitud
     * 
     * @param mixed $latitude
     * @param mixed $longitude
     * @param mixed $radius
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getNearbyProduct($userId, $radius = 10)
    {
        // Obtener las coordenadas del usuario que está aplicando el filtro
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Usuario no encontrado',
            ]);
        }
    
        // Obtener latitud y longitud del usuario
        $latitude = $user->latitude;
        $longitude = $user->longitude;
    
        // Buscar los products de los usuarios cercanos
        $products = Product::selectRaw("
            products.id, products.name, products.price, products.description, 
            ( 6371 * acos( cos( radians(?) ) * cos( radians( users.latitude ) ) * cos( radians( users.longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( users.latitude ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->join('user_product', 'products.id', '=', 'user_product.product_id') // Tabla intermedia
        ->join('users', 'user_product.user_id', '=', 'users.id') // Relación con usuarios
        ->having('distance', '<', $radius)  // Filtrar products dentro del radio
        ->orderBy('distance')  // Ordenar por distancia
        ->get();
    
        return response()->json([
            'status' => 200,
            'success' => true,
            'products' => $products,
        ]);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show(User $user)
    {
        $user->load('roles')->get();
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $role = Role::find($request->role_id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->surname1 = $request->surname1;
        $user->surname2 = $request->surname2;

        if(!empty($request->password)) {
            $user->password = Hash::make($request->password) ?? $user->password;
        }

        if ($user->save()) {
            if ($role) {
                $user->syncRoles($role);
            }
            return new UserResource($user);
        }
    }


    public function updateimg(Request $request)
    {

        $user = User::find($request->id);

        if($request->hasFile('picture')) {
            $user->media()->delete();
            $media = $user->addMediaFromRequest('picture')->preservingOriginal()->toMediaCollection('images-users');

        }
        $user =  User::with('media')->find($request->id);
        return  $user;
    }

    public function destroy(User $user)
    {
        $this->authorize('user-delete');
        $user->delete();

        return response()->noContent();
    }


}
