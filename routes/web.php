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

Route::Redirect('/','/home');

Route::get('profile', 'ProfileController@index');
Route::post('profile', 'ProfileController@update');

Route::get('/home', function(){
    return view('home');
});


Route::post('join', 'ChatkitController@join');
Route::get('chat', 'ChatkitController@chat')->name('chat');
Route::post('chat_logout', 'ChatkitController@logout')->name('chat_logout');

Auth::routes();

