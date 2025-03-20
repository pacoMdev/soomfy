<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Product;

class MessageController extends Controller
{
    public function index()
    {
        $authors = Message::all();
        return response()->json(['status' => 405, 'success' => true, 'data' => $authors]);
    }


    public function store(Request $request)
    {
        // Crea el message para guardar en base de datos
        $message = new Message();
        $message -> fullMessage = $request -> fullMessage;
        $message -> userDestination_id = $request -> userDestination_id;
        $message -> userRemitent_id = $request -> userRemitent_id;
        $message -> product_id = $request -> product_id;
        $message -> isReaded = $request -> isReaded;

        // Guardar en la base de datos
        $message -> save();

        return response()->json(data: ['status' => 200, 'success' => true, 'mensaje' => 'message guardado', 'message' => $message]);
    }

    public function show($id)
    {
        // busca por id
        $message = Message::findOrFail($id);

        return response()->json(['status' => 200, 'success' => true, 'data' => $message]);
    }
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        
        // Crea el message para guardar en base de datos
        $message -> fullMessage = $request -> fullMessage ?? $message -> fullMessage;
        $message -> userDestination_id = $request -> userDestination_id ?? $message -> userDestination_id;
        $message -> userRemitent_id = $request -> userRemitent_id ?? $message -> userRemitent_id;
        $message -> product_id = $request -> product_id ?? $message -> product_id;
        $message -> isReaded = $request -> isReaded ?? $message -> isReaded;

        // Guardar en la base de datos
        $message->save();
        
        return response()->json(['status' => 200, 'success' => true, 'mensaje' => 'message actualizado', 'message' => $message]);
    }


    public function delete($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        return response()->json(['status' => 200, 'success' => true, 'mensaje' => 'message eliminado']);
    }
    /**
     * Summary of getConversation
     * Obtiene todos los mensajes y los marca como leidos los mensajes enviados del remitente
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function getConversation(Request $request){   
        // El lector el el remitente     
        $messages = Message::where('product_id', $request->product_id)->where('userRemitent_id', $request->remitent_id)->where('userDestination_id', $request->destination_id)->get();
        dd($messages);

        foreach($messages as $message){
            $message -> isReaded = true;
            $message -> save();
        } 
        return response()->json(['status'=>200, 'Message'=>'Mensajes obtenidos y leidos']);
        
    }

    /**
     * Summary of sendMessage
     * Envia el mensaje al destinatario
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request){

        $product = Product::findOrFail($request -> product_id, );

        $userRemitent = User::findOrFail($request -> userRemitent);
        $userDestination = User::findOrFail($request -> userDestination);
        
           
        // Marca todo los mensajes del chat como leido por receptor, check en mensaje contrario

        $message = new Message();
        $message -> fullMessage = $request -> message;
        $message -> userDestination_id = $request -> userDestination_id;
        $message -> userRemitent_id = $request -> userRemitent;
        $message ->  product_id= $request -> product_id;
        $message -> isReaded = false;
        $message -> save();
        

        $data = [
            'userDestination' =>$userDestination,
            'userRemitent' =>$userRemitent,
            'product' =>$product,
            'message' =>$message,

        ];

        return response() -> json(['status' => 200, 'status'=>true, 'message'=>'Mensaje enviado', 'data'=>$data] );
}
}