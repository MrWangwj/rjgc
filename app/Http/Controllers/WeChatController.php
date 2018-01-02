<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class WeChatController extends Controller
{
    //
    public function serve()
    {

        $app = app('wechat.official_account');
        $app->server->push(function($message) use($app){

            switch ($message['MsgType']) {
                case 'event':
                    switch ($message['Event']){
                        case 'subscribe':
                            User::createUserByOpenId($message['FromUserName']);
                            return '欢迎关注科院课表查询系统';
                            break;
                    }

                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        return $app->server->serve();
    }

    public function test(){
        $app = app('wechat.official_account');

        dd($app->user->list());

    }

    public function menu(){
        $app = app('wechat.official_account');
        $buttons = [
            [
                "type" => "view",
                "name" => "导入课表",
                "url"  => url('/user/course/input/course')
            ],
            [
                "type" => "view",
                "name" => "我的课表",
                "url"  => url('/user/course/course/my')
            ],
        ];
        $app->menu->create($buttons);
    }
}
