<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\PostsController;
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

    // Route::apiResource('posts', PostControllerAdvance::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);

    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

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
    // Productos Favoritos
    // Agregar un producto a favoritos (POST)
    Route::post('add-favorite-post/{postId}', [PostControllerAdvance::class, 'addToFavorites']); 
    // Quitar productos de tus favoritos
    Route::delete('remove-favorite-post/{postId}', [PostControllerAdvance::class, 'quitarFavoritos']); 
    // Obtener productos favoritos del usuario (GET)
    Route::get('get-favorite-posts', [PostControllerAdvance::class, 'getFavoritePosts']);
});

// Users
Route::get('get-posts/nearby/{latitude}/{longitude}/{radius}', [UserController::class, 'getNearbyPost']);
Route::get('get-user-posts/{id}', [UserController::class, 'getUserPosts']);

// Categorías
Route::get('category-list', [CategoryController::class, 'getList']);

// Posts
// Obtener todas las publicaciones
Route::get('get-posts', [PostControllerAdvance::class, 'getPosts']);
// Obtener publicaciones por categoría
Route::get('get-category-posts/{id}', [PostControllerAdvance::class, 'getCategoryByPosts']);
// Obtener detalles de una publicación
Route::post('get-post/{id}', [PostControllerAdvance::class, 'getPost']);


Route::middleware('auth:sanctum')->get('/get-user-id', [UserController::class, 'getUserId']);



// Almacenar, Actualizar y Eliminar Posts
// Almacenar nueva publicación
Route::post('store-data-posts', [PostControllerAdvance::class, 'store']);
// Actualizar publicación existente
Route::put('posts/{id}', [PostControllerAdvance::class, 'update']);
// Eliminar publicación
Route::delete('posts/{id}', [PostControllerAdvance::class, 'delete']);

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
Route::get('getImagePost/2', [PostControllerAdvance::class, 'getImagePost']);
Route::post('getImagePost', [PostControllerAdvance::class, 'getImagePost']);
