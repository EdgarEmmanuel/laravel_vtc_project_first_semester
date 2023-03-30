<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthDriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }




    public function login(Request $request)
    {
       $credentials = $request->only('email', 'password');

       $chauffeur = Chauffeur::where("email", $credentials["email"])->first();

       if($chauffeur == null){
        return response()->json([
            'success'=> false,
            'code' => 403,
            'error' => [
                "message" => "aucun chaufeur avec cet email ou ce mot de passe"
            ]
        ]);
       } else {
        if(Hash::check($credentials["password"], $chauffeur->password)){
            //dd($chauffeur);
            $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+40 minutes')->getTimestamp();      // Add 60 seconds
            $serverName = "your.domain.name";
            $username   = "username";                                           // Retrieved from filtered POST data

            $data = [
                'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
                'iss'  => $serverName,                       // Issuer
                'nbf'  => $issuedAt->getTimestamp(),         // Not before
                'exp'  => $expire,                           // Expire
                'chauffeur' => $chauffeur,                     // User name
            ];
            return response()->json([
                'success' => true,
                'code' => 200,
                'error' => null,
                'token' => JWT::encode($data, env("JWT_SECRET"), 'HS256')
            ]);
        } else {
            return response()->json([
                'success'=> false,
                'code' => 403,
                'error' => [
                    "message" => "aucun chaufeur avec cet email ou ce mot de passe"
                ]
            ]);
        }
       }
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
