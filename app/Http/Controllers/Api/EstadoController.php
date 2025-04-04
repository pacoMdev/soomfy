<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Http\Resources\EstadoResource;
use Illuminate\Http\Request;

class EstadoController extends Controller
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
        $transactions = Estado::
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
        return EstadoResource::collection($transactions);
    }
    public function store(Estado $transactions)
    {
        $transaction = new Estado();
        $transaction->name = $transactions->name;
        $transaction->email = $transactions->email;
        $transaction->surname1 = $transactions->surname1;
        $transaction->surname2 = $transactions->surname2;
        $transaction->save();

        return new EstadoResource($transaction);
    }
    public function destroy(Estado $transaction)
    {
        $this->authorize('estado-delete');
        $transaction->delete();

        return response()->noContent();
    }
}
