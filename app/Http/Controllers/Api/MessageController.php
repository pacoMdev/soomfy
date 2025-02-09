<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;

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
        $message -> post_id = $request -> post_id;
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
        $message -> post_id = $request -> post_id ?? $message -> post_id;
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
}
