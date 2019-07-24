<?php

Route::group([
  'middleware' => 'api',
  'prefix' => 'v1',
], function () {
  // Login
  Route::resource('auth', 'Api\V1\AuthController')->only(['store']);
  Route::resource('date', 'Api\V1\DateController')->only(['show']);

  // With credentials
  Route::group([
    'middleware' => 'jwt.auth'
  ], function () {
    // Logout and refresh token
    Route::resource('auth', 'Api\V1\AuthController')->only(['show', 'update', 'destroy']);
    Route::resource('user', 'Api\V1\UserController')->only(['update']);

    // ADMIN routes
    Route::group([
      'middleware' => 'role:admin'
    ], function () {
      // User
      Route::resource('user', 'Api\V1\UserController')->only(['index', 'store', 'show', 'destroy']);
    });
  });
});
