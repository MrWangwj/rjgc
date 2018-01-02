<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function (){


    Route::get('/login', function(){
        return view('admin.login');
    });

    Route::post('/login', '\App\Admin\Controllers\LoginController@login');



    Route::group(['middleware' => ['admin.login']], function (){
        Route::get('/', function (){
            return view('admin.index');
        });
        Route::get('/logout', '\App\Admin\Controllers\LoginController@logout');


        Route::group(['prefix' => 'user'], function (){
            Route::get('/list', '\App\Admin\Controllers\UserController@list');
            Route::post('/disable/{user}', '\App\Admin\Controllers\UserController@disable');
        });



    });

});