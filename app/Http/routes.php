<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//setup all authentication routes (register,login,logout,reset-password,etc.)
Route::auth();
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
//Route::post('auth/register', 'Auth\AuthController@postRegister');


//Redirect register page get requests to login (only admins can register new users)
Route::get('/register', function(){
	return redirect('/login');
});
//Redirect register page post requests to login (only admins can register new users)
Route::post('/register', function(){
	return redirect('/login');
});


//========================= FRONT END ROUTES ========================
Route::get('/', 'FrontController@homepage');

//========================= BACK END ROUTES =========================
Route::get('/oficina-virtual', 'BackController@oficinaVirtual');
Route::post('/office/create/user', 'BackController@createUser');

//========================= API USER ROUTES ==============================
Route::get('/office/api/user/{id}', 'BackController@getUser');
Route::get('/office/api/userByEmail/{email}', 'BackController@getUserByEmail');
Route::get('/office/api/getdownlines/{user_id}', 'BackController@getUserDownlines');
Route::post('/office/api/mult_json_sync', 'BackController@multJsonSync');