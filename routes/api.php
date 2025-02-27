<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProductControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas de Autenticación (Sin middleware)
Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [ResetPasswordController::class, 'reset']);

// Rutas públicas de Categorías
Route::get('category-list', [CategoryController::class, 'getList']);

// Rutas públicas de Productos
Route::get('get-products', [ProductControllerAdvance::class, 'getProducts']);
Route::get('get-category-products/{id}', [ProductControllerAdvance::class, 'getCategoryByProducts']);
Route::get('get-product/nearby/{latitude}/{longitude}/{radius}', [UserController::class, 'getNearbyProducts']);
Route::get('products/{searchTerm}', [ProductControllerAdvance::class, 'index']);

// Rutas protegidas (Con middleware auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Categorías
    // Crea automaticamente, GET, POST , PUT,PATCH y DELETE
    Route::apiResource('categories', CategoryController::class)->except(['index']);

    // Productos
    Route::apiResource('products', ProductControllerAdvance::class);
    Route::post('store-data-products', [ProductControllerAdvance::class, 'store']);
    Route::put('product/{id}', [ProductControllerAdvance::class, 'update']);
    Route::delete('product/{id}', [ProductControllerAdvance::class, 'delete']);
    Route::post('gestor-favoritos/{productId}', [ProductControllerAdvance::class, 'gestorFavoritos']);
    Route::get('get-favorite-products', [ProductControllerAdvance::class, 'getFavoriteProducts']);

    // Usuario y Perfil
    Route::apiResource('users', UserController::class);
    Route::post('users/updateimg', [UserController::class, 'updateimg']);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);
    Route::get('/get-user-id', [UserController::class, 'getUserId']);

    // Roles y Permisos
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::get('abilities', [RoleController::class, 'getAbilities']);

    // Mensajes y Conversaciones
    Route::apiResource('message', MessageController::class);
    Route::post('getConversation', [MessageController::class, 'getConversation']);
    Route::post('sendMessage', [MessageController::class, 'sendMessage']);

    // Imágenes
    Route::get('getImagePost/2', [ProductControllerAdvance::class, 'getImagePost']);
    Route::post('getImagePost', [ProductControllerAdvance::class, 'getImagePost']);
});