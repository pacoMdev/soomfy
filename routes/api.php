<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

// Categoria
Route::apiResource('categories', CategoryController::class);
Route::get('/categories', [CategoryController::class, 'getCategories']); // Obtener todas las categorias
Route::get('category-list', [CategoryController::class, 'getList']); // Obtiene las categorias (Uso: selects)

    // Obtener publicaciones por categoría
    Route::get('get-category-products/{id}', [ProductControllerAdvance::class, 'getCategoryByProducts']);

// Productos
Route::apiResource('products', ProductControllerAdvance::class);
Route::get('products', [ProductControllerAdvance::class, 'getProducts']); // Provisional
// Productos del usuario
Route::get('get-user-products/', [UserController::class, 'getAuthProducts']); // Productos del usuario autenticado
Route::get('get-user-products/{id}', [UserController::class, 'getUserProducts']); // Productos del id de usuario recibido

// Productos favoritos
Route::post('gestor-favoritos/{productId}', [ProductControllerAdvance::class, 'gestorFavoritos']); // Agrega producto a favoritos

// Productos por ubicacion del usuario (EN PROCESO)
Route::get('get-product/nearby/{latitude}/{longitude}/{radius}', [UserController::class, 'getNearbyProducts']);

// protege las rutas
Route::group(['middleware' => 'auth:sanctum'], function() {
    // ApiResource hace lo siguiente
    // GET /users` - Index (Listar todos los usuarios)
    // POST /users` - Store (Crear un nuevo usuario)
    // GET /users/{user}` - Show (Mostrar un usuario específico)
    // PUT/PATCH /users/{user}` - Update (Actualizar un usuario)
    // DELETE /users/{user}` - Destroy (Eliminar un usuario)

    // Perfil
    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile/updateimg', [ProfileController::class,'updateimg']); // Actualizar imagen (usuario)
    Route::get('user', [ProfileController::class, 'user']); // Responsable de determinar si estas iniciado sesion o no
    Route::put('profile', [ProfileController::class, 'update']);

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