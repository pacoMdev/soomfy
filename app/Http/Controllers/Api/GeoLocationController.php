<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

class GeoLocationController extends Controller
{
    /**
     * API Key para Google Maps
     */
    private $apiKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiKey = config('services.google_maps.key', 'AIzaSyBlIXCsBY23dgXutYyJifkcoNdO46Z7VUo');
    }

    /**
     * Obtener dirección a partir de coordenadas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGeoLocation(Request $request)
    {
        try {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            
            if (!$latitude || !$longitude) {
                return response()->json(['error' => 'Se requiere latitud y longitud'], 400);
            }

            $client = new Client([
                'verify' => false, // Deshabilitar verificación SSL para desarrollo
                'timeout' => 10.0,
            ]);
            
            $url = "https://maps.googleapis.com/maps/api/geocode/json";
            $response = $client->get($url, [
                'query' => [
                    'latlng' => "$latitude,$longitude",
                    'key' => $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return response()->json($data);
        } catch (RequestException $e) {
            \Log::error('Error en API de geolocalización: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servicio de geolocalización'], 500);
        } catch (Exception $e) {
            \Log::error('Error general: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servicio'], 500);
        }
    }

    /**
     * Obtener coordenadas a partir de dirección
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGeocode(Request $request)
    {
        try {
            $address = $request->input('address');
            
            if (!$address) {
                return response()->json(['error' => 'Se requiere una dirección'], 400);
            }

            $client = new Client([
                'verify' => false, // Deshabilitar verificación SSL para desarrollo
                'timeout' => 10.0,
            ]);
            
            $url = "https://maps.googleapis.com/maps/api/geocode/json";
            $response = $client->get($url, [
                'query' => [
                    'address' => $address,
                    'key' => $this->apiKey
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return response()->json($data);
        } catch (RequestException $e) {
            \Log::error('Error en API de geocodificación: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servicio de geocodificación'], 500);
        } catch (Exception $e) {
            \Log::error('Error general: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servicio'], 500);
        }
    }
}