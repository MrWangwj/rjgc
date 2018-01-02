<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/1/1
 * Time: 下午4:15
 */

namespace App\Admin\Controllers;


use App\Admin;

class LoginController extends Controller {

    public function login(){
        $this->validate(request(), [
            'account' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('account', request('account'))->where('password', request('password'))->first();

        if($admin){
            session(['admin' => $admin]);
            return ['code' => 1, 'msg' => '登录成功'];
        }else{
            return ['code' => 0, 'msg' => '账号或密码错误'];
        }
    }

    public function logout(){
        session()->forget('admin');
        return redirect('/admin/login');
    }
}