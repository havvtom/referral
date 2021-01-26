<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Models\User;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    public function create()
    {

    }
    
    public function store(RegisterFormRequest $request)
    {
    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password)
    	]);

    	if(!$token = auth()->attempt($request->only('email', 'password'))){
    		return abort(401);
    	}

    	return (new UserResource(auth()->user()))
    			->additional([
    				'meta' => [
    					'token' => $token
    				]
    			]);
    }
}
