<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Product;
use Kreait\Firebase\Factory;
// use Google\Cloud\Core\Timestamp;

class MessageController extends Controller
{
    /**
     * obtiene al los usuarios que han mandado mensajes al producto (producto del usuario)
     * @param mixed $productId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsersConversations(Request $request){
        
        $allUsers = User::whereIn('id', $request->usersInterested)->with('media')->get();
        return $allUsers;
    }
}