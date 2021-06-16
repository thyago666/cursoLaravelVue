<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){



    	//autenticacao de e-mail e senha e retornar um token
    	$credenciais = $request->all(['email','password']);
    	$token = auth('api')->attempt($credenciais);
    	if($token){

    		return response()->json(['token'=> $token]);
    	}else{
    		return response()->json(['erro'=>'Usuario ou senha invalidos'],403);

    	}

 // return response()->json(['msg'=>'teste']);
    }

      public function logout(){

    	auth('api')->logout();
    	return response()->json(['msg'=>'Logout realizado com sucesso']);

    }
      public function refresh(){

    	$token = auth('api')->refresh();//o cliente tem que enviar um jwt valido
    	return response()->json(['token'=> $token]);

    }
      public function me(Request $request){

    return response()->json(auth()->user());

    }
}
