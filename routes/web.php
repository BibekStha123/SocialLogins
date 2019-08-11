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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/instagram', 'InstagramController@redirect');
Route::get('/instagram/callback', 'InstagramController@callback');

Route::get('/facebook', 'FacebookController@redirect');
Route::get('/facebook/callback', 'FacebookController@callback');

Route::group(['middleware' => [
    'auth'
]], function(){
    Route::post('/postPhoto', 'FbGraphController@publishToProfile');
    Route::post('/page', 'FbGraphController@publishToPage');

});


Route::get('/linkedin', 'LinkedinController@redirect');
Route::get('/linkedin/callback', 'LinkedinController@callback');

Route::get('/google', 'GoogleController@redirect');
Route::get('/google/callback', 'GoogleController@callback');

Route::get('/home', 'HomeController@index')->name('home');
