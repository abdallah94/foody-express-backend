<?php

namespace App\Http\Controllers;

use App\Category;
use App\Restaurant;
use JWTAuth;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        $restaurantID = $request->restaurant_id;
        $response = $this->checkPermission($request, "RESTAURANT");
        if ($response) {
            return $response;
        }
        if ($restaurantID) {
            $categories = Category::all()->where('restaurant_id', $restaurantID);
        }
        else{
            return Response([
                'error' => "no restaurant specified"
            ], 404);
        }
        return Response([
            'data' => $categories
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
        $name = $request->get('name');
        $restaurant_id = $request->get('restaurant_id');
        if (!$name) {
            return Response()->json([
                'error' => [
                    'message' => "please provide name"
                ]
            ], 422);
        }
        try {
            $category = Category::create(['name' => $name]);
            $restaurant = Restaurant::find($restaurant_id);
            if (!$restaurant) {
                return Response()->json([
                    'error' => [
                        'message' => "Restaurant doesn't exist"
                    ]
                ], 404);
            }
            $restaurant->categories()->save($category);
        } catch (Exception $e) {
            return Response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
        return Response()->json([
            'message' => 'category Created Successfully',
            'category' => $category
        ], 202);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        $category->items = $category->items;
        return Response()->json([
            'data' => $category
        ], 202);
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
