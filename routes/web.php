<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (config('shibboleth.emulate_idp') ) {

    Route::name('login')->get("login", '\StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@emulateLogin');
    # comment out for production shib
    Route::group(['middleware' => 'web'], function () {
        Route::get('emulated/idp', 'StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@emulateIdp');
        Route::post('emulated/idp', 'StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@emulateIdp');
        Route::get('emulated/login', 'StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@emulateLogin');
        Route::get('emulated/logout', 'StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@emulateLogout');
    });
}
else {
    Route::name('login')->get("login", '\StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@login');
    Route::group(['middleware' => 'web'], function () {
        Route::name('shibboleth-login')->get('/shibboleth-login', '\StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@login');
        Route::name('shibboleth-authenticate')->get('/shibboleth-authenticate', '\StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@idpAuthenticate');
        Route::name('shibboleth-logout')->get('/shibboleth-logout', '\StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@destroy');
    });

}

Route::get("/", "WelcomeController@index");

Route::group(['prefix' => '{board}'], function () {
    Route::get('/', 'BoardController@index');
	Route::get('/login', 'BoardController@login');
    Route::get('/loginRedirect', 'BoardController@loginRedirect');
    Route::post('/slackEndpoint', 'BoardController@slackEndpoint');
    Route::post('/slackSlashEndpoint', 'BoardController@slackSlashEndpoint');
});


Route::resource('admin/boards', 'Admin\BoardsController');