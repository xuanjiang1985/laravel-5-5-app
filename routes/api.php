<?php

//api
Route::post('/api/auth', 'JWTController@auth');
Route::prefix('api/v1')->middleware(['jwt.auth'])->group(function(){
	Route::get('/user', 'JWTController@user');
});
