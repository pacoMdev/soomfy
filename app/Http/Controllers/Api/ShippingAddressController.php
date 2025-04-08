<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Http\Resources\ShippingAddressResource;
use Spatie\Permission\Models\Role;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $roles = ShippingAddress::
            when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%'.request('search_title').'%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%'.request('search_global').'%');

                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

        return ShippingAddressResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ShippingAddressResource
     */
    public function store(Request $request)
    {
        $this->authorize('shippingAddress-create');

        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'web';

        if ($role->save()) {
            return new ShippingAddressResource($role);
        }

        return response()->json(['status' => 405, 'success' => false]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ShippingAddressResource
     */
    public function show(Role $role)
    {
        $this->authorize('shippingAddress-edit');

        return new ShippingAddressResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Role $role
     * @param Request $request
     * @return ShippingAddressResource
     * @throws AuthorizationException
     */
    public function update(Role $role, Request $request)
    {
        $this->authorize('shippingAddress-edit');

        $role->name = $request->name;

        if ($role->save()) {
            return new ShippingAddressResource($role);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
        $this->authorize('shippingAddress-delete');
        $role->delete();

        return response()->noContent();
    }

    public function getList()
    {
        return ShippingAddressResource::collection(ShippingAddress::all());
    }

    public function getDistributionsCenters(){
        $distributionCenters = ShippingAddress::where('role_address', '1')->get();  // role_address = 1 --> Centro de distribucion
        return ShippingAddressResource::collection($distributionCenters);
    }
    public function getProximityCenters(Request $request){
        
        $proximityCenters = ShippingAddress::where('role_address', '1')->get();  // role_address = 1 --> Centro de distribuicion
        return ShippingAddressResource::collection($proximityCenters);
    }
}
