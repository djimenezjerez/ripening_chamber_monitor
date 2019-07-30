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
      // Module
      Route::resource('module', 'Api\V1\ModuleController')->only(['index', 'show']);
      Route::get('module/{id}/permissions', 'Api\V1\ModuleController@permissions');
    });
  });
});
