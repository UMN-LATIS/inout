<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => '{board}'], function () {
	Route::model('user',\App\User::class);
	Route::get('/inout/getTeams', 'Api\InoutController@getTeams');
	Route::resource('inout', 'Api\InoutController', ['parameters' => ['inout'=>'user']]);
	Route::put('/inout/{user}/toggleStatus', 'Api\InoutController@toggleStatus');
	Route::get('/inout/{user}/{status}/{secret}', 'Api\InoutController@setStatus');
	Route::post('/inout/createUser', 'Api\InoutController@createUser');
});