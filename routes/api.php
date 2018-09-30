<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('restaurant', 'RestaurantController', ['only' => [
    'index', 'show', 'store', 'update', 'destroy'
]]);

Route::post('login', 'LoginController@login');

Route::resource('order', 'OrderController', ['only' => [
    'index', 'show', 'store', 'update', 'destroy'
]]);

Route::resource('item', 'ItemController', ['only' => [
    'show', 'store', 'update', 'destroy'
]]);
Route::resource('category', 'CategoryController', ['only' => [
    'index', 'show', 'store', 'update', 'destroy'
]]);
Route::resource('subscribe', 'SubscribeController', ['only' => [
    'show', 'store', 'update', 'destroy'
]]);
Route::resource('size', 'SizeController', ['only' => [
    'show', 'store', 'update', 'destroy', 'index'
]]);
Route::resource('extra', 'ExtraController', ['only' => [
    'show', 'store', 'update', 'destroy', 'index'
]]);
Route::resource('delivery', 'DeliveryController', ['only' => [
    'show', 'update', 'index'
]]);
Route::resource('add_restaurant', 'AddRestaurantController', ['only' => [
    'index', 'store'
]]);
Route::resource('rating', 'RatingController', ['only' => [
    'index', 'store'
]]);
Route::get('ratingSubmitted/{id}', 'RatingController@ratingSubmitted');
