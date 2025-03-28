<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Intervention\Image\Gd\Driver;
use function Laravel\Prompts\error;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;


class GoogleController extends Controller
{
    public function googleLogin(){
        return Socialite::driver('google')->redirect();
    }

    public function googleAuth(Request $request){
        dd('inside googleAuth', $request);
        try{
            // obtiene la informacion del usuario y la guardamos en base de datos
            $googleUser = Socialite::driver('google')
            ->user();
            dd($googleUser);

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user){
                // autentica y reidirije a dashboard
                Auth::login($user);
                return redirect()->route('/');

            }else{
                // crea al usuario con el google_id
                $newUser = new User();
            }


            return $googleUser;
        }catch(\Exception $error){
            return $error;
        }
        // dd($googleUser);
        // a partir de aqui se crea el usuario es de cir llama al User() new para crear uno nuevo, hay que pasar parametros ademas de token y renove token
    }
}
