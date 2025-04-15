<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

class StripeController extends Controller
{
    public function index() {
        return view('payment');
    }
    
    public function checkout(Request $request) {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'uds',
                        'product_data' => [
                            'name' => 'Producto de prueba',
                        ],
                        'unit_amount' => 10000, // $10.00 in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ]);

            return redirect($session->url, 303);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to create payment session: ' . $e->getMessage()]);
        }
    }
}
