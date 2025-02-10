<?php
use Illuminate\Support\Facades\Mail;
function getBackURL()
{
    $url = '';
    if (env('APP_ENV') == 'local') {
        $url = 'http://127.0.0.1:8000/';
    } elseif (env('APP_ENV') == 'dev') {
        $url = 'https://soomfy.isdev.com/';
    };
    return $url;
}

function sendEmail($email){

    try {
        Mail::to($email->data['to_email'])->send($email);
    } catch (\Exception $exception) {
        return $exception->getMessage(); // Devuelve el error si falla
    }

    return "Correo enviado correctamente";
}