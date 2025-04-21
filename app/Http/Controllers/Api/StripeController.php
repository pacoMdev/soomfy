<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request){
        // dd($request->product_id);

        // Configura tu clave secreta de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            // Crear la sesión de pago
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur', // Cambia la moneda según sea necesario
                            'product_data' => [
                                'name' => $request->title, // Nombre del producto
                                'images' => [$request->image],
                            ],
                            'unit_amount' => $request->amount*100, // Precio en centavos (1000 = $10.00)
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => url('/app/checkout-final?session_id={CHECKOUT_SESSION_ID}'), // URL de éxito
                'cancel_url' => url('/app/checkout?productId='.$request->product_id),   // URL de cancelación
            ]);

            // Redirigir al cliente a la página de pago de Stripe
            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
