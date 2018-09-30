<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $input = $request->all();
        if (!$token = JWTAuth::attempt($input)) {
            return response()->json(['error' => 'wrong email or password.']);
        }
        $user = JWTAuth::toUser($token);
        $user->restaurant = $user->restaurant;
        return response()->json(['token' => $token, 'user' => $user]);
    }
}
