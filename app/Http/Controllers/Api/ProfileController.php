<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->load('media');
        return $this->successResponse($profile, 'User found');

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

}
