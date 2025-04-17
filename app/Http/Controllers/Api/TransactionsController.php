<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Product;
use App\Models\Message;
use App\Mail\ConstructEmail;
use App\Models\ShippingAddress;
use App\Models\ShippingAddressTransaction;
use Stripe\FinancialConnections\Transaction;

class TransactionsController extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'userSeller_id', 'userBuyer_id', 'product_id', 'initialPrice', 'finalPrice', 'isToSend', 'isRegated', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $transactions = Transactions::
            when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            // ->when(request('search_title'), function ($query) {
            //     $query->where('name', 'like', '%'.request('search_title').'%');
            // })
            // ->when(request('search_global'), function ($query) {
            //     $query->where(function($q) {
            //         $q->where('id', request('search_global'))
            //             ->orWhere('name', 'like', '%'.request('search_global').'%');

            //     });
            // })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

            // retornar el el transactions construido
        return TransactionResource::collection($transactions);
    }
    public function store(Transactions $transactions)
    {
        $transaction = new Transactions();
        $transaction->name = $transactions->name;
        $transaction->email = $transactions->email;
        $transaction->surname1 = $transactions->surname1;
        $transaction->surname2 = $transactions->surname2;
        $transaction->save();

        return new TransactionResource($transaction);
    }
    public function destroy(Transactions $transaction)
    {
        $this->authorize('transaction-delete');
        $transaction->delete();

        return response()->noContent();
    }

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
        
        $userBuyer = auth()->user();
        $userSeller = User::findOrFail($request -> purchaseData['userSeller_id']);
        $product = Product::findOrFail($request -> purchaseData['product_id']);

        // segun tipo de envio 
        if ($request -> purchaseData['selectedMethod'] == '1'){     // Custom address
            $shippingAddress = new ShippingAddress();
            $shippingAddress -> address = $request -> purchaseData['shippingAddress']['newAddress'];
            $shippingAddress -> city = $request -> purchaseData['shippingAddress']['newCity'];
            $shippingAddress -> cp = $request -> purchaseData['shippingAddress']['newCp'];
            $shippingAddress -> country = $request -> purchaseData['shippingAddress']['newCountry'];
            $shippingAddress -> role_address = 2;
            $shippingAddress -> contact_name = $userBuyer -> name;
            $shippingAddress -> contact_phone = $userBuyer -> phone ?? '';
            $shippingAddress -> contact_email = $userBuyer -> email;

            $shippingAddress -> save();

        }else if($request -> purchaseData['selectedMethod'] == '2'){    // Selected address
            $shippingAddress = (object) $request -> purchaseData['selectedStablishment'];
        }

        $transaction = new Transactions();      // crea transaccion
        $transaction -> userSeller_id = $request -> purchaseData['userSeller_id']; 
        $transaction -> userBuyer_id = $userBuyer -> id; 
        $transaction -> product_id = $request -> purchaseData['product_id']; 
        $transaction -> initialPrice = $request -> purchaseData['price']; 
        $transaction -> finalPrice = $request -> purchaseData['price']; 
        $transaction -> isToSend = $request -> purchaseData['isToSend'] == 0 ? false : true; 
        $transaction -> isRegated = false; 
        $transaction -> delivery_type = $request ->purchaseData['selectedMethod'] == 1 ? 'home_delivery' : 'pickup_point';
        $transaction -> $request -> session_id ?? null;
        $transaction->save();

        $transaction -> shippingAddress() 
        -> attach($shippingAddress -> id, [
            'status' => 'pending',
            'tracking_number' => $this -> generateTN()
        ]);
        
        // EMAIL PURCHASE ---------------------------------------------------------------------------------------
        $data = [
            'from_email'    => 'soomfy@gmail.com',
            'from_name'     => 'Soomfy Buyer',
            'to_email'      => $userBuyer -> email,
            'to_name'       => $userBuyer -> name,
            'subject'       => '¡Tu compra ha sido confirmada! 📦',
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
            'subject'       => '¡Comparte tu opinión sobre tu última compra! 🔎',
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

    /**
     * obtiene todas las transacciones de compra del usuario
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getPurchase(Request $request){
        $userId = $request->userId;
        $transactions_id = Transactions::where('userBuyer_id', $userId)->pluck('id');

        $purchase = ShippingAddressTransaction::whereIn('transactions_id', $transactions_id)
            ->where('status', 'finished')
            ->with(['transaction.product.media', 'transaction.seller.media', 'transaction.buyer.media'])
            ->get();

        return $this->successResponse($purchase, 'Transaction found');
    }
    /**
     * obtiene todas las transacciones de venta del usuario
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getSales(Request $request){
        $userId = $request->userId;
        $transactions_id = Transactions::where('userSeller_id', $userId)->pluck('id');

        $purchase = ShippingAddressTransaction::whereIn('transactions_id', $transactions_id)
            ->where('status', 'finished')
            ->with(['transaction.product.media', 'transaction.seller.media', 'transaction.buyer.media'])
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

    public function historicMovements(Request $request){

        $historic = ShippingAddressTransaction::where('tracking_number', $request->trakingNumber)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($query) {
                $icon = match (strtolower($query->status)) {
                    'ordered' => 'pi pi-shopping-cart',
                    'processed' => 'pi pi-cog',
                    'to_pick' => 'pi pi-truck',
                    'finished' => 'pi pi-check',
                    default => 'pi pi-info-circle',
                };
                $query->icon = $icon;
                return $query;
            });
        // dd($historic);
        return response() -> json($historic);
    }
}
