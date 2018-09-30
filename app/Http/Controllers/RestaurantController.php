<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\User;
use Hash;
use JWTAuth;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt-auth')->only('store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        if ($search) {
            $restaurants = Restaurant::where("name", "LIKE", "%$search%")->get();
        } else {
            $restaurants = Restaurant::all();
        }
        foreach ($restaurants as $restaurant) {
            $restaurant->restaurantDeliveries = $restaurant->restaurantDeliveries;
        }
        return Response([
            'data' => $restaurants
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
        $response = $this->checkPermission($request);
        if ($response) {
            return $response;
        }
        if (!$request->get('name') | !$request->get('phone') | !$request->get('email') | !$request->get('password')) {
            return Response()->json([
                'error' => [
                    'message' => "please provide name,phone,email, and password"
                ]
            ], 422);
        }
        $password = Hash::make($request->get('password'));
        $user = User::create(['email' => $request->get('email'), 'password' => $password, 'role' => "RESTAURANT"]);
        $restaurant = Restaurant::create(['name' => $request->get('name'), 'phone' => $request->get('phone')]);
        $restaurant->user()->save($user);
        return Response()->json([
            'message' => 'Restaurant Created Successfully',
            'data' => $user
        ], 202);
    }

    private function checkPermission(Request $request)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        if ($user->role != "ADMIN") {
            return Response()->json([
                'error' => [
                    'message' => "You don't have permissions to call this API"
                ]
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return Response([
                'error' => [
                    'message' => 'restaurant does not exist'
                ]
            ], 404);
        }
        if ($restaurant->categories && count($restaurant->categories)) {
            $restaurant->categories = $restaurant->categories;
            foreach ($restaurant->categories as $category) {
                $category->items = $category->items;
                foreach ($category->items as $item) {
                    $item->sizes = $item->sizes;
                    $item->extras = $item->extras;
                }
            }
        } else {
            $restaurant->items = $restaurant->items;
            foreach ($restaurant->items as $item) {
                $item->sizes = $item->sizes;
                $item->extras = $item->extras;
            }

        }
        $restaurant->restaurantDeliveries = $restaurant->restaurantDeliveries;

        return Response([
            'restaurant' => $restaurant,
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
        $response = $this->checkPermission($request);
        if ($response) {
            return $response;
        }
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return Response([
                'error' => [
                    'message' => 'Restaurant does not exist'
                ]
            ], 404);
        }
        if ($request->name) {
            $restaurant->name = $request->name;
        }
        if ($request->phone) {
            $restaurant->phone = $request->phone;
        }
        $restaurant->save();
        return Response([
            'message' => 'Restaurant Updated Succesfully'
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->checkPermission($this->route());
        if ($response) {
            return $response;
        }
        Restaurant::destroy($id);
    }
}
