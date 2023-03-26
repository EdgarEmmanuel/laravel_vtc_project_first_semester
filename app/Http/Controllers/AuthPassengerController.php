<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{ User, BankCard };



class AuthPassengerController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }




    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'code' => 201,
                //'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() / 60 . ' hour'
                ]
            ]);
    }




    public function getMatricule(){
        return "SSAP" . date("YmdHis");
    }




    public function register(Request $request){
        try{
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'matricule' => $this->getMatricule(),
                'password' => Hash::make($request->password),
            ]);
    
    
            
            if($request->account_number !== null){
                $expiry_date = explode("/", $request->expiry_date);
    
                $bankCard = new BankCard;
                $bankCard->account_number = $request->account_number;
                $bankCard->cvv = $request->cvv;
                $bankCard->expiry_date = $request->expiry_date;
                $bankCard->expiry_date_month = $expiry_date[0];
                $bankCard->expiry_date_day = $expiry_date[1];
    
                $bankCard->user()->associate($user);
                $bankCard->save() ;
            }
    
    
            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                //'decoded_token' => Auth::payload()->toArray(),
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() / 60 . ' hour'
                ]
            ]);
        }catch(\Illuminate\Database\QueryException $e){
            return response()->json([
                'success'=> false,
                'code' => 500,
                'error' => [
                    "message" => $e->getMessage()
                ]
            ]);
        }
    }




    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }




    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }


}
