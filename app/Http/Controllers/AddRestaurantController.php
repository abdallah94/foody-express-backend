<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddRestaurant;

class AddRestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = AddRestaurant::all();
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
        try {
            $restaurant = AddRestaurant::create(['first_name' => $request->first_name, 'last_name' => $request->last_name,
                'restaurant' => $request->restaurant, 'phone' => $request->phone, 'email' => $request->email,
                'address' => $request->address]);
        } catch (Exception $e) {
            return Response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
        return Response()->json([
            'message' => 'request submitted successfully ',
            'request' => $restaurant
        ], 202);
    }

}
