<?php

Route::group([
  'middleware' => 'api',
  'prefix' => 'v1',
], function () {
  // Login
  Route::resource('auth', 'Api\V1\AuthController')->only(['store', 'update']);
  Route::resource('date', 'Api\V1\DateController')->only(['show']);

  // With credentials
  Route::group([
    'middleware' => 'jwt.auth'
  ], function () {
    // Logout and refresh token
    Route::resource('auth', 'Api\V1\AuthController')->only(['show', 'destroy']);
    Route::resource('user', 'Api\V1\UserController')->only(['update']);

    // ADMIN routes
    Route::group([
      'middleware' => 'role:admin'
    ], function () {
      // User
      Route::resource('user', 'Api\V1\UserController')->only(['index', 'store', 'show', 'destroy']);
      Route::get('user/{id}/role', 'Api\V1\UserController@get_roles');
      Route::post('user/{id}/role', 'Api\V1\UserController@set_roles');
      // Role
      Route::resource('role', 'Api\V1\RoleController')->only(['index', 'show']);
      Route::get('role/{id}/permission', 'Api\V1\RoleController@get_permissions');
      Route::post('role/{role_id}/permission/{permission_id}', 'Api\V1\RoleController@set_permission');
      Route::delete('role/{role_id}/permission/{permission_id}', 'Api\V1\RoleController@unset_permission');
      // Module
      Route::resource('module', 'Api\V1\ModuleController')->only(['index', 'show']);
      Route::get('module/{id}/permission', 'Api\V1\ModuleController@permissions');
      // Device
      Route::resource('device', 'Api\V1\DeviceController')->only(['index', 'store', 'show', 'update', 'destroy']);
      // Room
      Route::resource('room', 'Api\V1\RoomController')->only(['index', 'store', 'show', 'update', 'destroy']);
      Route::get('room/{id}/magnitude', 'Api\V1\RoomController@get_magnitudes');
      Route::post('room/{id}/magnitude', 'Api\V1\RoomController@set_magnitudes');
      Route::get('room/export/{id}/{from}/{to}', 'Api\V1\RoomController@export');
      // Magnitude
      Route::resource('magnitude', 'Api\V1\MagnitudeController')->only(['index', 
      'store', 'show', 'update', 'destroy']);
      Route::get('magnitude/{id}/room', 'Api\V1\MagnitudeController@get_rooms');
      // Measurement
      Route::resource('measurement', 'Api\V1\MeasurementController')->only(['index']);
    });
  });
});
