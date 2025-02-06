<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostsResource;
use App\Models\Posts;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostsController extends Controller
{
    public function index()
    {
        $authors = Posts::all();
        return response()->json(['status' => 405, 'success' => true, 'data' => $authors]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'max:255'],
                'surname' => '',
                'email' => ['required', 'unique:authors']
            ]
        );


        $data = $validator->validated();


        $posts = Posts::create($data);
        return response()->json(['status' => 405, 'success' => true, 'data' => $posts]);
    }


    public function show(Posts $posts)
    {
        $posts->load('categories')->get();
        return new PostsResource($posts);
    }
    public function update(Posts $posts, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => '',
                'description' => '',
                'price' => '',
                'estado' => '',
                'location' => '',
                'toSend' => '',
                'isDeleted' => '',
                'isBost' => '',
                'dimensionX' => '',
                'dimensionY' => '',
                'marca' => '',
                'color' => '',
                'category_id' => '',
            ]
        );
        $data = $validator->validated();
        $posts->update($data);
        return response()->json(['status' => 405, 'success' => true, 'data' => $posts]);
    }


    public function destroy(Posts $posts)
    {
        $posts->delete();
        return response()->json(['status' => 405, 'success' => true, 'data' => '']);
    }
    public function getImgPost(Request $request) 
    {
    }
}
