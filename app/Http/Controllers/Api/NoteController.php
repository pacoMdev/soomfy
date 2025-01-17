<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    //
    public function index(){
        $note = Note::all();
        return response()->json($note, 200);

    }//creacion de la nota 
    public function store(Request $request){
        $note = Note::create($request -> all());
        return response()->json($note, 201);

    }
    public function show($id){
        $note = Note::find($id);
        return response()->json($note, 200);

    }
    public function update(Request $request, $id){
        $note = Note::find($id);
        $note->update($request->all());
        return response()->json(["success" => true], 200);

    }
    public function delete($id){
        $note = Note::find($id);
        $note->delete();
        return response()->json(["success" => true], 200);

    }
}
