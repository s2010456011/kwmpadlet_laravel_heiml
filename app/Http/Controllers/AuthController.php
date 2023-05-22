<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        //middleware soll nicht bei login route aufgerufen werden, erst nachdem user eingelogged ist!
        $this->middleware('auth:api', ['except' =>['login']]);
    }

    //POST Request
    public function login(){
        //email und passwort holen und speichern
        // \request ist das gleiche wie $_GET
        $credentials = request(['email', 'password']);

        //token erzeugen
        //wenn gültiger Token zurückbekommt --> nutzer darf weiter, ansonsten sperren
        //attempt versucht mit den credentials in der Datenbank einen user zu finden mit diesen Daten

        //ungültiger token
        if(!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'Unauthorizes'], 401);
        }

        //gültiger token
        return $this->respondWithToken($token);
    }

    //macht aus JWT ein JSON Objekt und wird an Client geschickt
    //TTL = TimeToLife --> wie lange ist token gültig
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    //gibt aktuellen User aus AuthLibary zurück --> eingeloggter User
    public function me(){
        return respsone()->json(auth()->user());
    }

    //bei logout gültigen JWT zerstören --> nicht mehr gültig
    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    //bei neuem übermitteln des Tokens soll dieser erneuert werden, also Zeit wieder auf 0
    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }
}
