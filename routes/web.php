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
Route::get('/test', 'TestController@test3');
Route::get('/city', 'TestController@cityedit');
Route::get('/city/{id}', 'TestController@cityget');
Route::get('/district/{id}', 'TestController@districtget');
Route::get('/districtupdate', 'TestController@districtupdate');
Route::get('/friend/{city}', 'TestController@collectFriend');
Route::get('/provincetoname', 'TestController@provinceToName');
// Route::get('/info', function () {
//    echo phpinfo();
// });

Route::get('/', function () {
    return 1;
});
Route::get('/login', 'LoginController@getLogin');

//后台
require 'admin.php';