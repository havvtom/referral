<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function login(LoginFormRequest $request)
    {
    	if(!$token = auth()->attempt($request->only('email', 'password'))){
    		return response()->json([
    			'errors' => [
    				'email' => ['Sorry we could not sign you in with those details.']
    			]
    		], 422);
    	};
    	
    	return (new UserResource(auth()->user()))
    			->additional([
    				'meta' => [
    					'token' => $token
    				]
    			]);
    }
}
