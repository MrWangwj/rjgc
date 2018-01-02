<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    //设置所有字段可以create创建
    protected $guarded = [];

    //课表关联模型
    public function courses(){
        return $this->belongsToMany(Course::class, 'user_course',  'user_id','course_id');
    }


    //通过openid获取用户
    public static function getUserByOpenId($openid){
        return self::where('openid', $openid)->first();
    }

    //通过openID创建用户
    public static function createUserByOpenId($openid){
        $app = app('wechat.official_account');
        $user = $app->user->get($openid);

        $userData = [
            'openid' => $openid,
            'name' => $user['nickname'],
            'sex' => $user['sex'],
        ];
        return self::firstOrCreate($userData);
    }

}
