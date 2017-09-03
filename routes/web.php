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
Route::get('/test', 'TestController@test');
// Route::get('/info', function () {
//    echo phpinfo();
// });

Route::get('/', function () {
    return view('index');
});
Route::get('/login', 'LoginController@getLogin');
//admin center login
Route::get('/jacklogin','Admin\LoginController@getLogin');
Route::post('/jacklogin','Admin\LoginController@postLogin')->name('admin.login');
Route::prefix('jackadmin')->namespace('Admin')->middleware(['admin'])->group(function(){
	Route::get('/','LoginController@index')->name('admin');
	Route::get('/logout','LoginController@getLogout')->name('admin.logout');
	Route::middleware(['role:superAdmin|admin'])->group( function(){
		Route::get('/demo1','LoginController@demo1')->name('admin.demo1');
		Route::get('/demo2','LoginController@demo2')->name('admin.demo2');
	});
});
