<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credeciais = $request->all(['name','password']);

        $token = auth('api')->attempt($credeciais);

        dd($token);
    }

    public function logout()
    {

    }

    public function refresh()
    {

    }

    public function me()
    {

    }
}
