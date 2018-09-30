<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Order;

class RatingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response([
            'data' => Rating::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rating = Rating::create(['restaurant_rating' => $request->restaurant_rating, 'delivery_rating' => $request->delivery_rating,
            'foody_express_rating' => $request->foody_express_rating, 'remarks' => $request->remarks]);
        $order = Order::find($request->order_id);
        //TODO: attach items;
        $order->rating()->save($rating);
        return Response([
            'data' => $rating
        ], 200);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }

    public function ratingSubmitted($id){
        $order = Order::find($id);
        if($order && $order->rating){
            return Response([
                'isSubmitted' => true
            ], 200);
        }
        else{
            return Response([
                'isSubmitted' => false
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
