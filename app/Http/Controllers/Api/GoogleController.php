<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Intervention\Image\Gd\Driver;
use function Laravel\Prompts\error;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use ParagonIE\ConstantTime\Hex;

class GoogleController extends Controller
{
    // GOOGLE_AUTH_API ------------------------------------------------------------
    public function googleLogin(){
        return Socialite::driver('google')->redirect();
    }

    public function googleAuth(Request $request){
        $googleUser = Socialite::driver('google')
        ->user();
        dd($googleUser);
        // try{
        //     // obtiene la informacion del usuario y la guardamos en base de datos

        //     $user = User::where('google_id', $googleUser->id)->first();

        //     if ($user){
        //         // autentica y reidirije a dashboard
        //         Auth::login($user);
        //         return redirect()->route('/');

        //     }else{
        //         // crea al usuario con el google_id
        //         $newUser = new User();
        //         $newUser -> name = $googleUser -> name;
        //         $newUser -> surname1 = $googleUser -> surname;
        //         $newUser -> email = $googleUser -> email;
        //         $newUser -> password = Hash::make('password@12345');
        //         $newUser -> google_id = $googleUser -> id;
                
        //         $newUser -> save();
        //         Auth::login($newUser);
        //         return redirect()->route('/');
        //     }


        // }catch(\Exception $error){
        //     return $error;
        // }
    }


    // GOOGLE_MAPS_API ------------------------------------------------------------
    public function getAddress(Request $request){
        $apiKey = env('GOOGLE_API_KEY');
        $address = $request->query('address');

        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $address.', Spain',
            'key' => $apiKey
        ]);

        return $response->json();
    }

    public function geoLocation(Request $request){
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $apiKey = env('GOOGLE_API_KEY');

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "$latitude,$longitude",
            'key' => $apiKey
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Error al obtener la dirección'], 500);
        }

        return response()->json($response->json());
    }

    public function reverseGeocode(Request $request){
        $apiKey = env('GOOGLE_API_KEY');
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => $lat . ',' . $lng,
            'key' => $apiKey
        ]);

        // Pots aquí mateix retornar només la formatted_address si vols simplificar-ho
        return $response->json();
    }
}
