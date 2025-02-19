<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;

use App\Models\Category;
use App\Models\Post;
use App\Models\Post_image;
use App\Models\Transactions;
use App\Models\User;

use App\Mail\ConstructEmail;


class PostControllerAdvance extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'title', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $posts = Post::with('media')
            ->whereHas('categories', function ($query) {
                if (request('search_category')) {
                    $categories = explode(",", request('search_category'));
                    $query->whereIn('id', $categories);
                }
            })
            ->when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            ->when(request('search_title'), function ($query) {
                $query->where('title', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_content'), function ($query) {
                $query->where('content', 'like', '%' . request('search_content') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('title', 'like', '%' . request('search_global') . '%')
                        ->orWhere('content', 'like', '%' . request('search_global') . '%');

                });
            })
            ->when(!auth()->user()->hasPermissionTo('post-all'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

        return PostResource::collection($posts);
    }

    /**
     * Summary of store
     * Guarda la info del post
     * @param \App\Http\Requests\StorePostRequest $request
     * @return PostResource
     */
    public function store(StorePostRequest $request)
    {

        $this->authorize('post-create');
        
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();
        

        $post = new Post();
        $post -> title = $validatedData["title"];
        $post -> content = $validatedData["content"];
        $post -> estado = $validatedData["estado"] ?? "Nuevo";
        $post -> price = $validatedData["price"] ?? 99.99;
        // dd($validatedData, $request);


        $post = Post::create($validatedData);

        $categories = explode(",", $request->categories);
        $category = Category::findMany($categories);
        $post->categories()->sync($category);


        if ($request->hasFile('thumbnail')) {
            $post->addMediaFromRequest('thumbnail')->preservingOriginal()->toMediaCollection('images');
        }
        
        return new PostResource($post);
    }

    public function show(Post $post)
    {
        $this->authorize('post-edit');
        if ($post->user_id !== auth()->user()->id && !auth()->user()->hasPermissionTo('post-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own posts']);
        } else {
            return new PostResource($post);
        }
    }


    //NO edita imagen
    public function update(Post $post, StorePostRequest $request)
    {
        $this->authorize('post-edit');

        if ($post->user_id !== auth()->id() && !auth()->user()->hasPermissionTo('post-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own posts']);
        } else {
            $post->update($request->validated());

            $category = Category::findMany($request->categories);
            $post->categories()->sync($category);

            return new PostResource($post);
        }
    }

    public function destroy(Post $post)
    {
        $this->authorize('post-delete');
        if ($post->user_id !== auth()->id() && !auth()->user()->hasPermissionTo('post-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only delete your own posts']);
        } else {
            $post->delete();
            return response()->noContent();
        }
    }

    public function getPosts()
    {
        $posts = Post::with('categories')->with('media')->latest()->paginate();
        return PostResource::collection($posts);

    }

    public function getCategoryByPosts($id)
    {
        $posts = Post::whereRelation('categories', 'id', '=', $id)->paginate();

        return PostResource::collection($posts);
    }

    public function getPost($id)
    {
        return Post::with('categories', 'users', 'media')->findOrFail($id);
    }

    // Favoritos

    // Agregar a favoritos 
    public function agregarFavoritos($postId){
        $user = auth()->user();
        $post = Post::findOrFail($postId);
        // Agregar producto a favoritos, si no existe
        $user->favoritos()->syncWithoutDetaching([$postId]);
        return back();
    }

    public function quitarFavoritos($postId) {
        $user = auth()->user();
        $post = Post::findOrFail($postId);
         // Quitamos el producto de favoritos
         $user->favoritos()->detach($postId);

         return back(); // Volvemos a la página anterior
    }

     // Función para mostrar los productos favoritos
     public function getFavoritePosts(){
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $favoritos = $user->favoritos;

            return response()->json($favoritos);
    }

    public function sellPost(Request $request){
        $userSeller = User::findOrFail($request -> userSeller_id);
        $userBuyer = User::findOrFail($request -> userBuyer_id);
        
        if ($request -> initialPrice != $request -> finalPrice){
            $initialPrice = $request -> initialPrice;
            $finalPrice = $request -> finalPrice;
            $isRegated = true;
        }else{
            $initialPrice = $request -> initialPrice;
            $finalPrice = $request -> finalPrice;
            $isRegated = false;
        }

        $transaction = new Transactions();
        $transaction -> userSeller_id = $userSeller -> id;
        $transaction -> userBuyer_id = $userBuyer -> id;
        $transaction -> post_id = $request -> post_id;
        $transaction -> initialPrice = $initialPrice;
        $transaction -> finalPrice = $finalPrice;
        $transaction -> isToSend = $request -> isToSend;
        $transaction -> isRegated = $isRegated;

        $transaction -> save();

        $data = [
            'from_email' => 'soomfy@gmail.com',
            'from_name' => 'Soomfy',
            'to_email' => $userSeller['email'],
            'to_name' => $userSeller['name'],
            'subject' => 'Hey acabas de vender un producto',
            'view' => 'emails.welcome',
            'finalPrice' => $finalPrice,
            'userSeller' => $userSeller,
            'userBuyer' => $userBuyer,
        ];
        $email = new ConstructEmail($data);
        $data_email = sendEmail($email);


        return response() -> json(['status' => 200, ' succsss' => true, 'seller' => $userSeller, 'buyer' => $userBuyer, 'post' =>$transaction]);
    }
}
