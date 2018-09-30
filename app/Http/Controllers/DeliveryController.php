<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Restaurant;
use App\Order;
use App\Item;
use App\User;

class DeliveryController extends Controller
{
    private function checkPermission(Request $request, $role)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        if ($user->role != 'DELIVERY') {
            return Response()->json([
                'error' => [
                    'message' => "You don't have permissions to call this API"
                ]
            ], 403);
        }
        return null;
    }

    public function __construct()
    {
        $this->middleware('jwt-auth')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = $this->checkPermission($request, "DELIVERY");
        $show_latest = $request->show_latest;
        if ($response) {
            return $response;
        }
        if ($show_latest) {
            $orders = Order::orderBy('created_at', 'desc')->where('accepted', true)->take(50)->get();
        } else {
            $orders = Order::orderBy('created_at', 'desc')->where('accepted', true)->get();
        }
        return Response([
            'data' => $this->transformOrdersArray($orders)
        ], 200);
    }

    private function transformOrdersArray($orders)
    {
        foreach ($orders as $order) {
            $items = array_map([$this, 'transform'], $order->items->toArray());
            unset($order->items);
            $order->items = $items;
        }
        return $orders;
    }

    private function transform($item)
    {
        return [
            'item_id' => $item['id'],
            'item_name' => $item['name'],
            'item_description' => $item['description'],
            'price' => $item['price'],
            'number' => $item['pivot']['number']
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
