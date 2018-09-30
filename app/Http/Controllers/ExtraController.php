<?php

namespace App\Http\Controllers;
use JWTAuth;
use Illuminate\Http\Request;
use App\Extra;
use App\Item;

class ExtraController extends Controller
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
        $item_id = $request->item_id;
        $response = $this->checkPermission($request, "RESTAURANT");
        if ($response) {
            return $response;
        }
        if ($item_id) {
            $extras = Extra::all()->where('item_id', $item_id);
        }
        else{
            return Response([
                'error' => "no item specified"
            ], 404);
        }
        return Response([
            'data' => $extras
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
        $name = $request->get('name');
        $item_id = $request->get('item_id');
        $price = $request->get('price');
        if (!($name && $item_id && $price)) {
            return Response()->json([
                'error' => [
                    'message' => "please provide name,item_id, and price"
                ]
            ], 422);
        }
        try {
            $extra = Extra::create(['name' => $name,'price'=>$price]);
            $item = Item::find($item_id);
            if (!$item) {
                return Response()->json([
                    'error' => [
                        'message' => "Item doesn't exist"
                    ]
                ], 404);
            }
            $item->extras()->save($item);
        } catch (Exception $e) {
            return Response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
        return Response()->json([
            'message' => 'extra Created Successfully',
            'extra' => $extra
        ], 202);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    private function checkPermission(Request $request, $role)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        if ($user->role != 'ADMIN') {
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
}
