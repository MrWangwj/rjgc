<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/1/1
 * Time: 下午4:15
 */

namespace App\Admin\Controllers;


use App\User;

class UserController extends Controller {

    public function list(){
        $users = User::get();
        return view('admin.user.list', compact('users'));
    }

    public function disable(User $user){
        if($user->is_del == 1)
            $user->is_del = 0;
        else
            $user->is_del = 1;

        $r = $user->save();
        if($r)
            return ['code' => 1, 'msg' => '修改成功'];
        else
            return ['code' => 0, 'msg' => '修改失败'];
    }

}