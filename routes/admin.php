<?php
//admin center routes
Route::get('/jacklogin','Admin\LoginController@getLogin');
Route::post('/jacklogin','Admin\LoginController@postLogin')->name('admin.login');
Route::prefix('jackadmin')->namespace('Admin')->middleware(['admin'])->group(function(){
	Route::get('/','LoginController@index')->name('admin');
	Route::get('/logout','LoginController@getLogout')->name('admin.logout');
	Route::get('/change-psd','LoginController@changePassword')->name('admin.changePassword');
	Route::post('/change-psd','LoginController@changedPassword')->name('admin.changedPassword');
	//need permissions
	Route::middleware(['role:superAdmin|admin|editor|unadmin'])->group( function(){
		Route::get('/demo1','LoginController@demo1')->name('admin.demo1');
		Route::get('/demo2','LoginController@demo2')->name('admin.demo2');
		Route::get('/role','PermissionController@roleIndex')->name('admin.role');
		Route::get('/role/dispatch/{id}','PermissionController@roleDispatch')->name('admin.roleDispatch');
		Route::post('/role/dispatch/{id}','PermissionController@roleDispatched')->name('admin.roleDispatched');
		Route::get('/role/delete/{id}','PermissionController@roleDelete')->name('admin.roleDelete');
		Route::post('/role/delete/{id}','PermissionController@roleDeleted')->name('admin.roleDeleted');
		Route::get('/permission','PermissionController@permissionIndex')->name('admin.permission');
		Route::get('/permission/attach/{id}','PermissionController@permissionAttach')->name('admin.permissionAttach');
		Route::post('/permission/attached/{id}','PermissionController@permissionAttached')->name('admin.permissionAttached');
		//managers setting
		Route::get('manager','ManagerController@index')->name('admin.manager');
		Route::get('manager/show/{id}','ManagerController@show')->name('admin.managerShow');
		Route::put('manager/update/{id}','ManagerController@update')->name('admin.managerUpdate');
		Route::delete('manager/delete/{id}','ManagerController@delete')->name('admin.managerDelete');
		Route::get('manager/create','ManagerController@create')->name('admin.managerCreate');
		Route::post('manager/store','ManagerController@store')->name('admin.managerStore');
	});
});