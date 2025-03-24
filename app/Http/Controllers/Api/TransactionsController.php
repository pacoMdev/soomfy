<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Product;
use App\Models\Message;
use App\Mail\ConstructEmail;
use App\Models\ShippingAddress;

class TransactionsController extends Controller
{

    public function getAllTransictions()
    {
        $authors = Transactions::all();
        return response()->json(['status' => 405, 'success' => true, 'data' => $authors]);
    }

    /**
     * Generacion de la compra ficticia
     * @param \Illuminate\Http\Request $request son los id de vendedor y del producto que se pasan por url(front lo recoje y lo manda para back), ademas de la informacion de envio si es que tiene, back controla si hay que añadir o no
     * Manda emailde confirmacion y de informacion sobre venta al propietario del producto
     * @return mixed|\Illuminate\Http\JsonResponse  mensaje de todo OK
     */
    public function fakePurchase(Request $request)
    {
        // dd($request -> shippingAddress['newAddress']);

        $userBuyer = auth()->user();
        $userSeller = User::findOrFail($request -> userSeller_id);
        $product = Product::findOrFail($request -> product_id);

        $transaction = new Transactions();

        $transaction -> userSeller_id = $request -> userSeller_id; 
        $transaction -> userBuyer_id = $userBuyer -> id; 
        $transaction -> product_id = $request -> product_id; 
        $transaction -> initialPrice = $request -> price; 
        $transaction -> finalPrice = $request -> price; 
        $transaction -> isToSend = true; 
        $transaction -> isRegated = false; 
        if ($request -> isToSend == 0){
            $transaction -> isToSend = false; 
            
            $message = new Message();

            $message -> fullMessage = 'Hola, te informamos que ' . $userBuyer->name . ' ha adquirido tu producto. Por favor, coordina el envío o entrega. ¡Gracias!”';
            $message -> userDestination_id = $userSeller -> id;
            $message -> userRemitent_id = $userBuyer -> id;
            $message -> product_id = $request -> product_id;
            $message -> isReaded = false;

            $message -> save();
        }else{
            $transaction -> save();

            $shippingAddress = new ShippingAddress();

            $shippingAddress -> transaction_id = $transaction -> id;
            $shippingAddress -> user_id = $transaction -> userBuyer_id;
            $shippingAddress -> address = $request -> shippingAddress['newAddress'];
            $shippingAddress -> city = $request -> shippingAddress['newCity'];
            $shippingAddress -> postal_code = $request -> shippingAddress['newCp'];
            $shippingAddress -> country = $request -> shippingAddress['newCountry'];
            $shippingAddress -> status = 'Pendiente';
            $shippingAddress -> tracking_number = $this->generateTN();

            $shippingAddress -> save();
        }



        
        // EMAIL PURCHASE ---------------------------------------------------------------------------------------
        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Buyer',
            'to_email'      => $userBuyer -> email,
            'to_name'       => $userBuyer -> name,
            'subject'       => '¡Tu compra ha sido confirmada! 📦✨',
            'view'          => 'emails.purchaseProduct',
            'finalPrice'    => $transaction -> finalPrice,
            'userSeller'    => $userSeller,
            'userBuyer'     => $userBuyer,
            'product'       => $product,
        ];

        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);

        // EMAIL OPINION ---------------------------------------------------------------------------------------
        $token=bin2hex(random_bytes(32));

        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Valoration',
            'to_email'      => $userBuyer -> email,
            'to_name'       => $userBuyer -> name,
            'subject'       => '¡Comparte tu opinión sobre tu última compra!',
            'view'          => 'emails.valoration',
            'finalPrice'    => $transaction -> finalPrice,
            'userSeller'    => $userSeller,
            'userBuyer'     => $userBuyer,
            'product'       => $product,
            'saleDate'      => $transaction -> created_at,
            'url'           => getenv('APP_URL') . '/opinion?userIdS=' . $userSeller->id . '&userIdB=' . $userBuyer->id . '&productId=' . $product->id . '&token=' . $token,
        ];
        // Manda el email
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);

        return response()->json(data: ['status' => 200, 'success' => true, 'mensaje' => 'fackePurchser OK and sended email purchase', 'message' => $transaction]);
    }
    /**
     * actualiza la informacion de la transaccion
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updatePurchase(Request $request)
    {
        $purchase = Transactions::findOrFail($request->id);
        
        $purchase -> userBuyer_id = $request -> buyer_id ?? null;
        $purchase -> userSeller_id = $request -> seller_id ?? null;
        $purchase -> initialPrice = $request -> initialPrice ?? 0;
        $purchase -> finalPrice = $request -> finalPrice ?? 0;
        $purchase -> isToSend = $request -> isToSend ?? false;
        $purchase -> isRegated = $request -> isRegated ?? false;

        $purchase->save();
        
        return response()->json(['status' => 200, 'success' => true, 'mensaje' => 'compra actualizada', 'message' => $purchase]);
    }


    public function deletePurchase(Request $request)
    {
        $purchase = Transactions::findOrFail($request->id);
        $purchase->delete();
        return response()->json(['status' => 200, 'success' => true, 'mensaje' => 'message eliminado']);
    }
    /**
     * Genera un numeto de seguimiento(traking_number (TN))
     * en base a prefijo, fecha y random numbers from bin2hex
     * @return string with the traking_number (TN)
     */
    function generateTN() {
        $prefix = 'TN';
        $timestamp = time();
        $randomNumber = strtoupper(bin2hex(random_bytes(5)));
        return $prefix . $timestamp . $randomNumber;
    }

    public function getPurchase(Request $request){
        $userId = $request->userId;
        $purchase = User::find($userId)->purchase()
        ->with(['product', 'seller', 'buyer'])
        ->get();

        return $this->successResponse($purchase, 'Transaction found');
    }
    
    public function getSales(Request $request){
        $userId = $request->userId;
        $purchase = User::find($userId)->sales()
        ->with(['product', 'seller', 'buyer'])
        ->get();

        return $this->successResponse($purchase, 'Transaction found');
    }
    public function sellProduct(Request $request){
        $userSeller = auth()->user();
        $userBuyer = User::findOrFail($request -> userBuyer_id);
        $product = Product::findOrFail($request -> product_id);
        
        if ($request -> finalPrice == 0){
            $initialPrice = $product-> price;
            $finalPrice = $product -> price;
            $isRegated = false;
        }else{
            $initialPrice = $product -> price;
            $finalPrice = $request -> finalPrice;
            $isRegated = true;
        }

        $transaction = new Transactions();
        $transaction -> userSeller_id = $userSeller -> id;
        $transaction -> userBuyer_id = $userBuyer -> id;
        $transaction -> product_id = $product -> id;
        $transaction -> initialPrice = $initialPrice;
        $transaction -> finalPrice = $finalPrice;
        $transaction -> isToSend = $request -> isToSend;
        $transaction -> isRegated = $isRegated;

        $transaction -> save();

        // EMAIL SELL ---------------------------------------------------------------------------------------
        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Seller',
            'to_email'      => $userSeller -> email,
            'to_name'       => $userSeller -> name,
            'subject'       => 'Hey acabas de vender un producto',
            'view'          => 'emails.sellProduct',
            'finalPrice'    => $finalPrice,
            'userSeller'    => $userSeller,
            'userBuyer'     => $userBuyer,
            'product'       => $product,
            'saleDate'      => $transaction -> created_at,
            'url'           => getenv('APP_URL') . '/products/detalle/' . $product -> id,
        ];
        // Manda el email
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);

        // EMAIL OPINION ---------------------------------------------------------------------------------------
        $token=bin2hex(random_bytes(32));

        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Valoration',
            'to_email'      => $userBuyer -> email,
            'to_name'       => $userBuyer -> name,
            'subject'       => '¡Comparte tu opinión sobre tu última compra!',
            'view'          => 'emails.valoration',
            'finalPrice'    => $finalPrice,
            'userSeller'    => $userSeller,
            'userBuyer'     => $userBuyer,
            'product'       => $product,
            'saleDate'      => $transaction -> created_at,
            'url'           => getenv('APP_URL') . '/opinion?userIdS=' . $userSeller->id . '&userIdB=' . $userBuyer->id . '&productId=' . $product->id . '&token=' . $token,
        ];
        // Manda el email
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);


        return response() -> json(['status' => 200, ' succsss' => true, 'seller' => $userSeller, 'buyer' => $userBuyer, 'product' =>$transaction]);
    }
}
