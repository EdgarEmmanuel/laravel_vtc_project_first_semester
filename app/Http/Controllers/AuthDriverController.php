<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthDriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }




    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

       dd($credentials);
    }




    public function register(Request $request){
        dd($request->all());
    }




    public function getMatricule(){
        return "REDI" . date("YmdHis");
    }
}
