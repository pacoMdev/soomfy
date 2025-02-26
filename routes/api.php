<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\productsController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

// protege las rutas
Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::apiResource('users', UserController::class);

    Route::post('users/updateimg', [UserController::class,'updateimg']); //Listar

    // Route::apiResource('seed_products', ProductControllerAdvance::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);

    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    Route::post('/categories', [CategoryController::class, 'getCategories']);



    Route::get('/seed_products/{search?}', [ProductControllerAdvance::class, 'index']);


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
    // Product Favoritos
    // Agregar un producto a favoritos (POST)
    Route::post('gestor-favoritos/{productId}', [ProductControllerAdvance::class, 'gestorFavoritos']);
    // Obtener seed_products favoritos del usuario (GET)
    Route::get('get-favorite-seed_products', [ProductControllerAdvance::class, 'getFavoriteProducts']);
});

// Users
Route::get('get-product/nearby/{latitude}/{longitude}/{radius}', [UserController::class, 'getNearbyProducts']);
Route::get('get-user-seed_products/{id}', [UserController::class, 'getUserProducts']);

// Categorías
Route::get('category-list', [CategoryController::class, 'getList']);

// seed_products
Route::get('get-seed_products', [ProductControllerAdvance::class, 'getProducts']);

// Obtener todas las publicaciones
Route::get('get-seed_products', [ProductControllerAdvance::class, 'getProducts']);
// Obtener publicaciones por categoría
Route::get('get-category-seed_products/{id}', [ProductControllerAdvance::class, 'getCategoryByProducts']);
// Obtener detalles de una publicación
Route::post('get-product/{id}', [ProductControllerAdvance::class, 'getProduct']);


Route::middleware('auth:sanctum')->get('/get-user-id', [UserController::class, 'getUserId']);

// Filtro producto buscador
// Le pasamos lo que hemos buscado al index
Route::get('seed_products/{searchTerm}', [ProductControllerAdvance::class, 'index']);

// Almacenar, Actualizar y Eliminar seed_products
// Almacenar nueva publicación
Route::post('store-data-seed_products', [ProductControllerAdvance::class, 'store']);
// Actualizar publicación existente
Route::put('product/{id}', [ProductControllerAdvance::class, 'update']);
// Eliminar publicación
Route::delete('product/{id}', [ProductControllerAdvance::class, 'delete']);

// Mensajes
Route::get('message', [MessageController::class, 'index']);
Route::post('message', [MessageController::class, 'store']);
Route::get('message/{id}', [MessageController::class, 'show']);
Route::put('message/{id}', [MessageController::class, 'update']);
Route::delete('message/{id}', [MessageController::class, 'delete']);

// Conversaciones
Route::post('getConversation', [MessageController::class, 'getConversation']);
Route::post('sendMessage', [MessageController::class, 'sendMessage']);

// Publicación de imagen de producto
Route::get('getImagePost/2', [ProductControllerAdvance::class, 'getImagePost']);
Route::post('getImagePost', [ProductControllerAdvance::class, 'getImagePost']);
