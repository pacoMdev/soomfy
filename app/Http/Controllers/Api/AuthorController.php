<?php


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthorController extends Controller
{
   public function index(){
       $authors = Author::all();
       return response()->json(['status'=>405,'success'=>true,'data'=>$authors]);
   }


   public function store(Request $request){
       $validator = Validator::make(
           $request->all(),
           [
               'name'=> ['required','max:255'],
               'surname' => '',
               'email'=>['required','unique:authors']
           ]
       );


       $data = $validator->validated();


       $author = Author::create($data);
       return response()->json(['status'=>405,'success'=>true,'data'=>$author]);


       //




       /*
       $author = new Author();
       $author->name = $request->name;
       $author->surname = $request->surname;
       $author->email = $request->email;
       $author->save();
       return response()->json(['status'=>405,'success'=>true,'data'=>$author]);
       */
   }


   public function show(Author $author)
    {
        $author->load('roles')->get();
        return new AuthorResource($author);
    }
   public function update(Author $author, Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name'=> ['required','max:255'],
                'surname' => ''
            ]
        );
        $data = $validator->validated();
        $author->update($data);
        return response()->json(['status'=>405,'success'=>true,'data'=>$author]);

   }


   public function destroy(Author $author){
       $author->delete();
       return response()->json(['status'=>405,'success'=>true,'data'=>'']);
   }




}
