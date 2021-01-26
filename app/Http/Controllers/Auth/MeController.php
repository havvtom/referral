<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function __construct()
	{
		$this->middleware(['auth:api']);
	}
	
    public function me(Request $request)
    {
    	return new UserResource($request->user());
    }
}
