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
});

Route::get('category-list', [CategoryController::class, 'getList']);

Route::get('get-posts', [PostControllerAdvance::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostControllerAdvance::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostControllerAdvance::class, 'getPost']);

// añade la ruta de la api para redirigir a la clase 
//crud de apis
Route::get('note', [NoteController::class, 'index']) -> name('note.index');
Route::post('note', [NoteController::class, 'store']) -> name('note.store');
Route::get('note/{id}', action: [NoteController::class, 'show']) -> name('note.show');
Route::put('note/{id}', action: [NoteController::class, 'update']) -> name('note.update');
Route::delete('note/{id}', action: [NoteController::class, 'delete']) -> name('note.delete');

Route::get('author', [AuthorController::class, 'index']) -> name('author.index');
Route::post('author', action: [AuthorController::class, 'store']);
Route::delete('author/{author}', action: [AuthorController::class, 'delete']);
Route::get('author/{author}', action: [AuthorController::class, 'index']);
Route::get('author/{author}', action: [AuthorController::class, 'update']);

Route::get('posts', [PostsController::class, 'index']) -> name('posts.index');
Route::post('posts', action: [PostControllerAdvance::class, 'store']);
Route::get('posts/{id}', action: [PostsController::class, 'show']);
Route::get('posts/nearby/{latitude}/{longitude}/{radius}', action: [PostsController::class, 'getNearbyPost']);
Route::put('posts/{id}', action: [PostsController::class, 'update']);
Route::delete('posts/{id}', action: [PostsController::class, 'delete']);

Route::get('message', [MessageController::class, 'index']);
Route::post('message', action: [MessageController::class, 'store']);
Route::get('message/{id}', action: [MessageController::class, 'show']);
Route::put('message/{id}', action: [MessageController::class, 'update']);
Route::delete('message/{id}', action: [MessageController::class, 'delete']);

Route::post('sellPost', action: [PostsController::class, 'sellPost']);

Route::post('getConversation', action: [MessageController::class, 'getConversation']);
Route::post('sendMessage', action: [MessageController::class, 'sendMessage']);
Route::post('getImagePost', action: [PostsController::class, 'getImagePost']);