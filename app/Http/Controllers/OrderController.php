<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Restaurant;
use App\Order;
use App\Item;
use App\User;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt-auth')->except('store');
    }

    public function index(Request $request)
    {
        $restaurantID = $request->restaurant_id;
        $show_latest = $request->show_latest;
        $response = $this->checkPermission($request, "RESTAURANT");
        if ($response) {
            return $response;
        }
        if ($restaurantID) {
            if ($show_latest) {
                $orders = Order::orderBy('created_at', 'desc')->where('restaurant_id', $restaurantID)->take(20)->get();
            } else {
                $orders = Order::orderBy('created_at', 'desc')->where('restaurant_id', $restaurantID)->get();
            }
        } else {
            $response = $this->checkPermission($request, "ADMIN");
            if ($response) {
                return $response;
            }
            $orders = Order::orderBy('created_at', 'desc')->get();
        }
        return Response([
            'data' => $this->transformOrdersArray($orders)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $total = $request->get('total');
        $location = $request->get('location');
        $restaurant_id = $request->get('restaurant_id');
        $items = $request->get('items');
        $remarks = $request->get('remarks');
        $delivery_time=$request->get('delivery_time');
        $delivery_fee=$request->get('delivery_fee');
        if (!$remarks) {
            $remarks = "";
        }
        $delivery = $request->get('delivery');
        if (!$first_name | !$last_name | !$phone | !$total | !$location | !$restaurant_id | !$items) {
            return Response()->json([
                'error' => [
                    'message' => "please provide first_name,last_name,phone,location,total,restaurant_id, and items"
                ]
            ], 422);
        }
        try {
            $order = Order::create(['first_name' => $first_name, 'last_name' => $last_name, 'phone' => $phone,
                'email' => $email, 'total' => $total, 'location' => $location, 'delivery' => $delivery, 'remarks' => $remarks,
                'delivery_time'=>$delivery_time,'delivery_fee'=>$delivery_fee]);
            $restaurant = Restaurant::find($restaurant_id);
            //TODO: attach items;
            $order->items()->attach($items);
            $restaurant->orders()->save($order);
        } catch (Exception $e) {
            return Response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
        return Response()->json([
            'message' => 'Order Created Successfully',
            'data' => $order
        ], 202);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $response = $this->checkOrderPermission($request, "RESTAURANT", $id);
        if ($response) {
            return $response;
        }
        $order = Order::find($id);
        if (!$order) {
            return Response([
                'error' => [
                    'message' => 'Order does not exist'
                ]
            ], 404);
        }
        $order->items = $order->items;

        return Response([
            'data' => $this->transformOrder($order),
        ], 200);
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
        $response = $this->checkOrderPermission($request, "RESTAURANT", $id);
        if ($response) {
            return $response;
        }

        $order = Order::find($id);
        if (!$order) {
            return Response([
                'error' => [
                    'message' => 'Order does not exist'
                ]
            ], 404);
        }
        if ($request->accepted) {
            $order->accepted = $request->accepted;
        }

        if ($request->delivery_accepted) {
            $order->delivery_accepted = $request->delivery_accepted;
        }

        $order->save();
        return Response([
            'message' => 'order Updated Successfully'
        ], 202);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $response = $this->checkPermission($request, "ADMIN");
        if ($response) {
            return $response;
        }
        Restaurant::destroy($id);
    }

    private function checkPermission(Request $request, $role)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        if ($user->role != 'ADMIN' && $user->role != 'DELIVERY') {
            if ($role == "RESTAURANT") {
                if ($user->restaurant_id != $request->restaurant_id) {
                    return Response()->json([
                        'error' => [
                            'message' => "You don't have permissions to call this API"
                        ]
                    ], 403);
                }
            }
        }
        return null;
    }

    private function checkOrderPermission(Request $request, $role, $id)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        $order = Order::find($id);
        if ($user->role != 'ADMIN' && $user->role != 'DELIVERY') {
            if ($role == "RESTAURANT") {
                if ($user->restaurant_id != $order->restaurant_id) {
                    return Response()->json([
                        'error' => [
                            'message' => "You don't have permissions to call this API"
                        ]
                    ], 403);
                }
            }
        }
        return null;
    }

    private function transformOrder($order)
    {
        $items = array_map([$this, 'transform'], $order->items->toArray());
        unset($order->items);
        $order->items = $items;
        return $order;
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
            'number' => $item['pivot']['number'],
            'size' => $item['pivot']['size'],
            'extras' => $item['pivot']['extras'],
            'notes' => $item['pivot']['notes'],
        ];
    }
}
