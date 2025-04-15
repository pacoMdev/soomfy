<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\UserOpinion;
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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->surname1 = $request->surname1;
        $user->surname2 = $request->surname2;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            // Si hay un rol especificado, asignarlo con manejo de guard correcto
            if ($request->role_id) {
                $role = Role::find($request->role_id);
                if ($role) {
                    // Asignar el rol directamente usando el nombre del rol que ya existe en la DB
                    $user->assignRole($role->name);
                }
            }
            
            return new UserResource($user);
        }
        
        return response()->json(['message' => 'Error al crear el usuario'], 500);
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
        $user->name = $request->name;
        $user->email = $request->email;
        $user->surname1 = $request->surname1;
        $user->surname2 = $request->surname2;
        $user->latitude = $request->latitude ?? $user->latitude;
        $user->longitude = $request->longitude ?? $user->longitude;

        if(!empty($request->password)) {
            $user->password = Hash::make($request->password) ?? $user->password;
        }

        if ($user->save()) {
            // Si hay un rol especificado, sincronizarlo con manejo de guard correcto
            if ($request->role_id) {
                $role = Role::find($request->role_id);
                if ($role) {
                    // Sincronizar usando el nombre del rol
                    $user->syncRoles([$role->name]);
                }
            }
            
            return new UserResource($user);
        }
        
        return response()->json(['message' => 'Error al actualizar el usuario'], 500);
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

    function getAuthProducts(){
        $user = auth()->user();
        // dd($user);
        $products = Product::with('user_product')
            ->where('user_id', $user->id)
            ->latest()->paginate();

        return $products;
    }

    public function getUserProducts($id)
    {
        try {
            $products = Product::where('user_id', $id)
                ->with(['estado', 'categories', 'media'])
                ->get();

            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function valorate(Request $request){
        $user = auth()->user();

        $userB = User::find($user->id)
            ->first();

        $userB -> opinions() 
        -> attach($request -> productId, [
            'title' => $request -> title,
            'destription' => $request -> description,
            'calification' => $request -> rating,
            'token' => $request -> token,
        ]);

        return response() -> json(['opinion' => $userB->opinions(), 'status' => 200]);
    }
    public function getValorations(Request $request){
        $userId = $request->userId;
        // $reviews = UserOpinion::whereIn('product_id', function ($query) use ($userId) {
        //     $query->select('product_id')
        //           ->from('transactions')
        //           ->where('userSeller_id', $userId);
        // })->with(['user', 'product'])->get();
        // Obtener productos vendidos por el usuario
        $productIds = \DB::table('transactions')
        ->where('userSeller_id', $userId)
        ->pluck('product_id');

        // Obtener opiniones sobre esos productos desde la tabla intermedia
        $reviews = UserOpinion::whereIn('product_id', $productIds)
        ->with(['user.media', 'product.media']) // ya que tienes .with('media') en las relaciones
        ->get();
        return $this->successResponse($reviews, 'Reviews found');
    }
    public function checkReview(Request $request){
        $valoration = UserOpinion::where('token', $request->token)->first();
        if($valoration!=null){
            return response()->json(['check'=>true]);
        }
        return response()->json(['check'=>false]);
    }


}