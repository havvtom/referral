<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Referrals\ReferralController;
use App\Http\Controllers\TaskController;
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

Route::group(['prefix' => 'projects', 'middleware' => 'auth'], function () {
	Route::get('/', [ProjectController::class, 'index']);
	Route::post('/', [ProjectController::class, 'store']);
	Route::get('/{project}', [ProjectController::class, 'show']);
	Route::patch('/{project}', [ProjectController::class, 'update']);
	Route::delete('/{project}', [ProjectController::class, 'destroy']);
	Route::post('/{project}/tasks', [TaskController::class, 'store']);
	Route::patch('/{project}/tasks/{task}', [TaskController::class, 'update']);
	Route::delete('/{project}/tasks/{task}', [TaskController::class, 'destroy']);
});

Route::get('register', [RegisterController::class, 'create'])->name('register');

Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'as' => 'auth.'], function () {
	Route::post('/register', [RegisterController::class, 'store']);
	Route::post('/login', [LoginController::class, 'login']);
	Route::post('/logout', [LogoutController::class, 'logout']);
	Route::get('/user', [MeController::class, 'me']);
});

Route::group(['prefix' => 'referrals', 'namespace' => 'Referrals', 'middleware' => ('auth')], function () {
	Route::get('/', [ReferralController::class, 'index']);
	Route::post('/{project}', [ReferralController::class, 'store']);
});