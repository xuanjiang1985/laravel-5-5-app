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
// 前台
Route::get('/test', 'TestController@ganji');
Route::get('/alg', 'AlgController@index');
Route::get('/qr', 'AlgController@getQr');
Route::post('/qr/store', 'AlgController@storeQr');
Route::get('/qr/sort/{id}', 'AlgController@sortQr');
Route::get('/algws', 'AlgController@broadcast');
// Route::get('/city', 'TestController@cityedit');
// Route::get('/city/{id}', 'TestController@cityget');
// Route::get('/district/{id}', 'TestController@districtget');
// Route::get('/districtupdate', 'TestController@districtupdate');
// Route::get('/friend/{city}', 'TestController@collectFriend');
// Route::get('/provincetoname', 'TestController@provinceToName');
Route::get('/', function () {
    echo phpinfo();
});
Route::get('/login', 'LoginController@getLogin');
//后台
require 'admin.php';