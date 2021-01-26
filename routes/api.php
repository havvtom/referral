<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Referrals\ReferralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('register', [RegisterController::class, 'create'])->name('register');

Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'as' => 'auth.'], function () {
	Route::post('/register', [RegisterController::class, 'store']);
	Route::post('/login', [LoginController::class, 'login']);
	Route::get('/user', [MeController::class, 'me']);
});

Route::group(['prefix' => 'referrals', 'namespace' => 'Referrals', 'middleware' => ('auth')], function () {
	Route::get('/', [ReferralController::class, 'index']);
	Route::post('/', [ReferralController::class, 'store']);
});