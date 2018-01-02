<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>管理员登录</title>
    <link href="/unit/layui_login/css/default.css" rel="stylesheet" type="text/css" />
    <!--必要样式-->
    <link href="/unit/layui_login/css/styles.css" rel="stylesheet" type="text/css" />
    <link href="/unit/layui_login/css/demo.css" rel="stylesheet" type="text/css" />
    <link href="/unit/layui_login/css/loaders.css" rel="stylesheet" type="text/css" />
    <link href="/unit/layui-v2.2.45/layui/css/layui.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class='login'>
    <div class='login_title'>
        <span>管理员登录</span>
    </div>
    <div class='login_fields'>
        <div class='login_fields__user'>
            <div class='icon'>
                <img alt="" src='/unit/layui_login/img/user_icon_copy.png'>
            </div>
            <input name="login" placeholder='用户名' maxlength="16" type='text' autocomplete="off" id="account"/>
            <div class='validation'>
                <img alt="" src='/unit/layui_login/img/tick.png'>
            </div>
        </div>
        <div class='login_fields__password'>
            <div class='icon'>
                <img alt="" src='/unit/layui_login/img/lock_icon_copy.png'>
            </div>
            <input name="pwd" placeholder='密码' maxlength="16" type='password' autocomplete="off" id="password">
            <div class='validation'>
                <img alt="" src='/unit/layui_login/img/tick.png'>
            </div>
        </div>
        <div class='login_fields__password'>
            <p id="msg" style="padding-left: 30px;display: block;height: 15px;color: red;"> </p>
        </div>


        {{--<div class='login_fields__password'>--}}
            {{--<div class='icon'>--}}
                {{--<img alt="" src='/unit/layui_login/img/key.png'>--}}
            {{--</div>--}}
            {{--<input name="code" placeholder='验证码' maxlength="4" type='text' name="ValidateNum" autocomplete="off">--}}
            {{--<div class='validation' style="opacity: 1; right: -5px;top: -3px;">--}}
                {{--<canvas class="J_codeimg" id="myCanvas" onclick="Code();">对不起，您的浏览器不支持canvas，请下载最新版浏览器!</canvas>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class='login_fields__submit'>
            <input type='button' value='登录' onclick="login()">
        </div>
    </div>
    <div class='success'>
    </div>
    <div class='disclaimer'>
        <p>欢迎登录后台管理系统</p>
    </div>
</div>



<script type="text/javascript" src="/unit/layui_login/js/jquery.min.js"></script>

<script type="text/javascript" src="/unit/layui_login/js/jquery-ui.min.js"></script>
<script type="text/javascript" src='/unit/layui_login/js/stopExecutionOnTimeout.js?t=1'></script>
<script type="text/javascript" src="/unit/layui_login/layui/layui.js"></script>
<script type="text/javascript" src="/unit/layui_login/js/Particleground.js"></script>
<script type="text/javascript" src="/unit/layui_login/js/Treatment.js"></script>
<script type="text/javascript" src="/unit/layui_login/js/jquery.mockjax.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    $(document).keypress(function (e) {
        // 回车键事件
        if (e.which === 13) {
            $('input[type="button"]').click();
        }
    });
    //粒子背景特效
    $('body').particleground({
        dotColor: '#E8DFE8',
        lineColor: '#133b88'
    });

    function login() {
        let account = $('#account').val(),
            password = $('#password').val();

        $.post(
            '/admin/login',
            {
                account: account,
                password: password,
            },
            function (data) {

                if(data.code === 1){
                    window.location = '/admin'

                }else{
                    $('#msg').text(data.msg);
                }
            }
        );
    }


</script>

</body>
</html>
