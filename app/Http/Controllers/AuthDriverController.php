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
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+40 minutes')->getTimestamp();

            $data = [
                'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
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
            
            return $this->registerSecondaryChauffeur($request);
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
        try{
            // verifier que l'email du principal ne possede pas plus de deux chauffeurs
            $chauffeur_principal = Chauffeur::where("email", $request->email_principal_driver)->first();

            $count_number_associate = Chauffeur::where("email", $request->email_principal_driver)->count();

            if($chauffeur_principal != null){
                if($count_number_associate == 2){
                    return response()->json([
                        'success'=> false,
                        'code' => 403,
                        'error' => null,
                        'informations' => [
                            "message" => "Ce chauffeur possede deja un nombre limite d'associes"
                        ]
                    ]);
                } else {
                     //dd($request->all());
                     Chauffeur::create([
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'matricule' => $this->getMatricule(),
                        'password' => Hash::make($request->password),
                        'pays' => $request->pays,
                        'ville' => $request->ville,
                        'principal_driver_id' => $chauffeur_principal->driver_id
                    ]);

                    return response()->json(
                        [
                            'success' => true,
                            'code' => 201,
                            'error' => null
                        ]
                    );
                }
            } else {
                return response()->json([
                    'success'=> false,
                    'code' => 403,
                    'error' => [
                        "message" => "aucun chaufeur avec cet email"
                    ]
                ]);
            }
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




    public function getMatricule(){
        return "REDI" . date("YmdHis");
    }
}
