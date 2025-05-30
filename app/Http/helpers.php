<?php
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;
function getBackURL()
{
    $url = '';
    if (env('APP_ENV') == 'local') {
        $url = 'http://127.0.0.1:8000/';
    }elseif (env('APP_ENV') == 'production') {
        $url = 'https://soomfy-production.up.railway.app';
    };
    return $url;
}

function sendEmail($email){
    // Implementar colas con redis para alijerar la carga desde server y poder añadir condificones, logs y reintentar el envio

    try {
        Mail::to($email->data['to_email'])->send($email);
    } catch (\Exception $exception) {
        return $exception->getMessage(); // Devuelve el error si falla
    }

    return "Correo enviado correctamente";
}
function firestoreDB(){
    $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
    $firestore = $factory->createFirestore();
    return $firestore->database();
}