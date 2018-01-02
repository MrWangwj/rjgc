
@extends('user.layout.layout')

@section('title', '提示')


@section('content')
<div class="weui-msg">
    <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">封禁用户</h2>
        <p class="weui-msg__desc">您的账户已被封禁，请联系管理员 -QQ:123456789</p>
    </div>
    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__links">
                <a href="http://marchsoft.cn/" class="weui-footer__link">三月小组</a>
            </p>
            <p class="weui-footer__text">Copyright © 2006-2017</p>
        </div>
    </div>
</div>
@endsection