<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Restaurant;
use App\Category;
use App\Extra;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt-auth')->only('store', 'update', 'destroy');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //TODO:PERMISSION ONLY FOR CURRENT RESTAURANT
    public function store(Request $request)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $restaurant_id = $request->get('restaurant_id');
        $category_id = $request->get('category_id');
        $sizes = $request->get('sizes');
        $extras = $request->get('extras');
        if (!$name | !$description | !$price | !$restaurant_id) {
            return Response()->json([
                'error' => [
                    'message' => "please provide name, description, price, and restaurant_id"
                ]
            ], 422);
        }
        try {
            $item = Item::create(['name' => $name, 'description' => $description, 'price' => $price]);
            $file = $request->file('image');
            if ($file) {
                $path = $file->store('public');
                $item->imageUrl = $path;
                $item->save();
            }
            $restaurant = Restaurant::find($restaurant_id);
            if (!$restaurant) {
                return Response()->json([
                    'error' => [
                        'message' => "Restaurant doesn't exist"
                    ]
                ], 404);
            }
            if ($category_id) {
                $category = Category::find($category_id);
                if (!$category) {
                    return Response()->json([
                        'error' => [
                            'message' => "Category doesn't exist"
                        ]
                    ], 404);
                }
                $category->items()->save($item);
            }
            if ($sizes) {
                $item->sizes()->attach($sizes);
            }
            $item->save();
            if ($extras) {
                $extrasModels = [];
                foreach ($extras as $extra) {
                    $extrasModels[] = new Extra($extra);
                }

                $item->extras()->saveMany($extrasModels);
            }
            $restaurant->items()->save($item);
        } catch (Exception $e) {
            return Response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
        return Response()->json([
            'message' => 'Item Created Successfully',
            'item' => $item
        ], 202);
    }

    public function show($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return Response([
                'error' => [
                    'message' => 'Item does not exist'
                ]
            ], 404);
        }
        return Response([
            'item' => $item,
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
        $item = Item::find($id);
        $sizes = $request->get('sizes');
        $extras = $request->get('extras');
        if (!$item) {
            return Response([
                'error' => [
                    'message' => 'Item does not exist'
                ]
            ], 404);
        }
        if ($request->name) {
            $item->name = $request->name;
        }
        if ($request->description) {
            $item->description = $request->description;
        }
        if ($request->price) {
            $item->price = $request->price;
        }
        $category_id = $request->get('category_id');
        if ($category_id) {
            $category = Category::find($category_id);
            if (!$category) {
                return Response()->json([
                    'error' => [
                        'message' => "Category doesn't exist"
                    ]
                ], 404);
            }
            $category->items()->save($item);
        }
        $file = $request->image;
        if ($file) {
            $imageUrl = $item->imageUrl;
            if ($imageUrl) {
                Storage::delete($imageUrl);
            }
            return Response([
                'message' => $file
            ], 202);
            $path = $file->store('public');
            $item->imageUrl = $path;
        }
        if ($sizes) {
            $item->sizes()->attach($sizes);
        }
        $item->save();
        if ($extras) {
            $extrasModels = [];
            foreach ($extras as $extra) {
                $extrasModels[] = new Extra($extra);
            }

            $item->extras()->saveMany($extrasModels);
        }
        return Response([
            'message' => 'Item Updated Successfully',
            'item' => $item
        ], 202);
    }

    public function destroy($id)
    {
        Item::destroy($id);
    }
}
