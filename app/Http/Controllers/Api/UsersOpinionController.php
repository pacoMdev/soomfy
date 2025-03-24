<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserOpinion;


class UsersOpinionController extends Controller
{
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
        $userOpinion -> token = $request -> token;

        $userOpinion -> save();
    }

}
