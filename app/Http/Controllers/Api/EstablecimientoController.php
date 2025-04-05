<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EstablecimientoResource;
use Illuminate\Http\Request;
use App\Models\Establecimiento;

class EstablecimientoController extends Controller
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
        $establecimiento = Establecimiento::
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

            // retornar el el establecimiento construido
        return EstablecimientoResource::collection($establecimiento);
    }
    public function store(Establecimiento $establecimiento)
    {
        $token = bin2hex(random_bytes(32));
        $estab = new Establecimiento();

        $estab->nombre = $establecimiento->nombre;
        $estab->direccion = $establecimiento->direccion;
        $estab->zip = $establecimiento->zip;
        $estab->poblacion = $establecimiento->poblacion;
        $estab->ciudad = $establecimiento->ciudad;
        $estab->telefono = $establecimiento->telefono;
        $estab->email = $establecimiento->email;
        $estab->nombre_comercial = $establecimiento->nombre_comercial;

        $estab->save();

        return new EstablecimientoResource($estab);
    }
    public function destroy(Establecimiento $establecimiento)
    {
        $this->authorize('establecimiento-delete');
        $establecimiento->delete();

        return response()->noContent();
    }
}
