<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpinionResource;
use App\Models\UserOpinion;


class UsersOpinionController extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'userSeller_id', 'userBuyer_id', 'product_id', 'initialPrice', 'finalPrice', 'isToSend', 'isRegated', 'created_at'])) {
            $orderColumn = 'id';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'asc';
        }
        $transactions = UserOpinion::
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
        return OpinionResource::collection($transactions);
    }
    public function store(UserOpinion $requestOpinion)
    {
        $token = bin2hex(random_bytes(32));
        $opinion = new UserOpinion();

        $opinion->title = $requestOpinion->title;
        $opinion->description = $requestOpinion->description;
        $opinion->calification = $requestOpinion->calification;
        $opinion->token = $token;
        $opinion->product_id = $requestOpinion->product_id;
        $opinion->user_id = $requestOpinion->user_id;

        $opinion->save();

        return new OpinionResource($opinion);
    }
    public function destroy(UserOpinion $transaction)
    {
        $this->authorize('opinions-delete');
        $transaction->delete();

        return response()->noContent();
    }

    public function getValorations(Request $request){
        $userId = $request->userId;
        $reviews = UserOpinion::whereIn('product_id', function ($query) use ($userId) {
            $query->select('product_id')
                  ->from('transactions')
                  ->where('userSeller_id', $userId);
        })->with(['user', 'product'])->get();
        return $this->successResponse($reviews, 'Reviews found');
    }
    public function checkReview(Request $request){
        $valoration = UserOpinion::where('token', $request->token)->first();
        if($valoration!=null){
            return response()->json(['check'=>true]);
        }
        return response()->json(['check'=>false]);
    }
    public function valorate(Request $request){
        $userOpinion = new UserOpinion();
        $userOpinion -> title = $request -> title;
        $userOpinion -> destription = $request -> description;
        $userOpinion -> calification = $request -> rating;
        $userOpinion -> product_id = $request -> productId;
        $userOpinion -> user_id = $request -> userBuyer;

        $userOpinion -> save();
        return response() -> json(['opinion' => $userOpinion, 'status' => 200]);
    }

}
