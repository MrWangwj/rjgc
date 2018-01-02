<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class UserPower
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $openid =session('wechat.oauth_user')->id;
        $user = User::getUserByOpenId($openid);

        if($user->is_del == 1)
            return redirect('/user/msg/disable');
        return $next($request);
    }
}
