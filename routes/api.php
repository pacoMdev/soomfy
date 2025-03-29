<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\UsersOpinionController;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Transactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Reset password || forgot password
Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

// Categoria
Route::apiResource('categories', CategoryController::class);
Route::get('/categories', [CategoryController::class, 'getCategories']); // Obtener todas las categorias
Route::get('category-list', [CategoryController::class, 'getList']); // Obtiene las categorias (Uso: selects)

// Estado
Route::get('estado-list', [ProductControllerAdvance::class, 'getEstadoList']); // Obtiene las categorias (Uso: selects)

// Obtener publicaciones por categoría
Route::get('get-category-products/{id}', [ProductControllerAdvance::class, 'getCategoryByProducts']);

// Productos
Route::apiResource('products', ProductControllerAdvance::class);
Route::get('products', [ProductControllerAdvance::class, 'getProducts']); // Provisional
Route::get('get-user-products/', [UserController::class, 'getAuthProducts']); // Productos del usuario autenticado
Route::get('get-user-products/{id}', [UserController::class, 'getUserProducts']); // Productos del id de usuario recibido
Route::get('/products/{id}', [ProfileController::class, 'getUserByProductId']); // Productos del id de usuario recibido
Route::get('/getUsersConversations/{id}', [MessageController::class, 'getUsersConversations']); // Productos del id de usuario recibido
Route::get('/checkReview', [UsersOpinionController::class, 'checkReview']);
Route::post('/valorate', [UsersOpinionController::class, 'valorate']);

// Productos favoritos
Route::post('gestor-favoritos/{productId}', [ProductControllerAdvance::class, 'gestorFavoritos']); // Agrega producto a favoritos

// Productos por ubicacion del usuario (EN PROCESO)
Route::get('get-product/nearby/{latitude}/{longitude}/{radius}', [UserController::class, 'getNearbyProducts']);

 // GOOGLE AUTH
 Route::middleware(['web'])->group(function() {
     Route::get('auth/google', [GoogleController::class, 'googleLogin'])->name('auth.google');
     Route::get('auth/google-callback', [GoogleController::class, 'googleAuth'])->name('auth.google.callback');
 });

 // protect the routes
Route::group(['middleware' => 'auth:sanctum'], function() {

    // Buy || Sell
    Route::post('fakePurchaseProduct', [TransactionsController::class, 'fakePurchase']);
    Route::post('sellProduct', [Transactionscontroller::class, 'sellProduct']);

    // Perfil
    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile/updateimg', [ProfileController::class,'updateimg']); // Actualizar imagen (usuario)
    Route::get('user', [ProfileController::class, 'user']); // Responsable de determinar si estas iniciado sesion o no
    Route::put('profile', [ProfileController::class, 'update']);
    Route::get('profile/{id}', [ProfileController::class, 'getUserInfo']);
    Route::post('getAllToSell', [ProfileController::class, 'getAllToSell']);
    Route::post('getPurchase', [TransactionsController::class, 'getPurchase']);
    Route::post('getSales', [Transactionscontroller::class, 'getSales']);
    Route::post('getValorations', [UsersOpinionController::class, 'getValorations']);

    // Usuario
    Route::apiResource('users', UserController::class);
    Route::post('users/updateimg', [UserController::class,'updateimg']); // Actualizar imagen de perfil (udmin)

    // Productos favoritos
    Route::get('get-favorite-products', [ProductControllerAdvance::class, 'getFavoriteProducts']); // Obtener products favoritos

    // Mensajes
    Route::get('message', [MessageController::class, 'index']);
    Route::post('message', [MessageController::class, 'store']);
    Route::get('message/{id}', [MessageController::class, 'show']);
    Route::put('message/{id}', [MessageController::class, 'update']);
    Route::delete('message/{id}', [MessageController::class, 'delete']);

    // Conversaciones
    Route::post('getConversation', [MessageController::class, 'getConversation']);
    Route::post('sendMessage', [MessageController::class, 'sendMessage']);

    // Roles
    Route::apiResource('roles', RoleController::class);
    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);

    // Transactions
    Route::apiResource('transactions', TransactionsController::class);
    
    // GEOCODE GOOGLE_MAPS
    Route::get('/geocode', [GoogleController::class, 'geoCode']);
    Route::get('geoLocation', [GoogleController::class, 'geoLocation']);
    Route::get('/reverse-geocode', [GoogleController::class, 'reverseGeocode']);

    // Habilidades
    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});