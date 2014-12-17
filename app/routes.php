<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('oauth', 'OAuthController@getAuthorize');
Route::post('oauth', 'OAuthController@postAuthorize');
Route::post('oauth/access_token', 'OAuthController@postAccessToken');

Route::get('me', 'HomeController@me');
Route::get('login', 'HomeController@getLogin');
Route::post('login', 'HomeController@postLogin');
Route::get('register', 'HomeController@getRegister');
Route::post('register', 'HomeController@postRegister');
Route::get('active', 'HomeController@getActive');
Route::post('active', 'HomeController@postActive');
