<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        
        if($request->email_principal_driver != null){
            return $this->registerSecondaryDriver($request);
        } else {
            return $this->registerPrincipalChauffeur($request);
        }
    }



    public function registerPrincipalChauffeur(Request $request){
        try{
            Chauffeur::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'matricule' => $this->getMatricule(),
                'password' => Hash::make($request->password),
                'pays' => $request->pays,
                'ville' => $request->ville,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'code' => 201,
                    'error' => null
                ]
            );
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



    public function registerSecondaryChauffeur(Request $request){
        // verifier que l'email du principal ne possede pas plus de deux chauffeurs
        dd($request->all());
    }




    public function getMatricule(){
        return "REDI" . date("YmdHis");
    }
}
