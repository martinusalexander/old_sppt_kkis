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

//Route::get('/', function () {
//    return view('welcome');
//});

// For auth middleware
// Reference: https://stackoverflow.com/a/29303878
Route::post('login', ['as' => 'login', 'uses' => 'UserController@login']);

// User related urls
Route::match(['get', 'post'], '/login', 'UserController@login');
Route::match(['post'], '/register', 'UserController@register');
Route::match(['get'], '/activateaccount/{token}', 'UserController@activate');
Route::match(['get'], '/logout', 'UserController@logout');
Route::match(['get', 'post'], '/updateprofile', 'UserController@update_profile');
Route::match(['get', 'post'], '/changepassword', 'UserController@change_password');
Route::match(['get', 'post'], '/forgetpassword', 'UserController@forget_password');
Route::match(['post'], '/resetpassword/', 'UserController@reset_password');
Route::match(['get'], '/resetpassword/{token}', 'UserController@reset_password');


// Public related urls
Route::match(['get'], '/', 'PublicController@main_menu');