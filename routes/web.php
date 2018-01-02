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

use Illuminate\Support\Facades\Route;

Route::any('/wechat', "WeChatController@serve");
Route::any('/menu', 'WeChatController@menu');

Route::any('/test', 'WeChatController@test');

Route::get('/user/msg/disable', function (){
    return view('user.msg.disable');
});

Route::group(['middleware' => ['web', 'wechat.oauth:snsapi_userinfo', 'user.status'], 'prefix' => 'user'],function (){
    Route::get('/tt', function(){
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user);
    });


    Route::group(['prefix' => 'course'], function (){

        //教务管理
        Route::group(['prefix' => 'edu'], function (){
            //教务系统登录页面渲染
            Route::get('/login', function () {
                return view('user.course.login');
            });

            //教务系统的验证码
            Route::get('/login/validate/{t}', 'CourseController@eduLoginValidate');
            //教务管理登录
            Route::post('/login', 'CourseController@eduLogin');
        });

        //导入
        Route::group(['prefix' => 'input'], function (){
            //显示预览导入信息页面渲染
            Route::get('/course', 'CourseController@inputCourse');
            //导入课表
            Route::post('/set', 'CourseController@setInput');
        });


        //我得课表
        Route::group(['prefix' => 'course'], function(){
            //课表页面渲染
            Route::get('/my', 'CourseController@userCourse');
            //删除课程
            Route::post('/delete', 'CourseController@delete');


            //课表编辑页面渲染
            Route::get('/edit/{course}', 'CourseController@getEdit');

            //课表编辑
            Route::post('/edit', 'CourseController@setEdit');

            //添加课表页面渲染
            Route::get('/add', function (){
                return view('user.course.add');
            });

            //添加课表
            Route::post('/add', 'CourseController@setAdd');

        });

    });
});

include('admin.php');