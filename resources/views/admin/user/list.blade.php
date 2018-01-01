@extends('admin.layout.layout')


@section('content')

    <table class="layui-table">
        <thead>
        <tr>
            <th>id</th>
            <th>昵称</th>
            <th>性别</th>
            <th>openid</th>
            <th>邮箱</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->sex }}</td>
                <td>{{ $user->openid }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->is_del == 1)
                        <button class="layui-btn" onclick="operation(0, {{ $user->id }})">启用</button>
                    @else
                        <button class="layui-btn layui-btn-danger" onclick="operation(1, {{ $user->id }})">禁用</button>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

@endsection

@section('js')
    <script src="/unit/layui_login/layui/lay/modules/layer.js"></script>
    <script>
        function operation(type, id){
            if(type !== 0){
                layer.msg('确定要禁用用户吗？', {
                    time: 0 //不自动关闭
                    ,btn: ['确定', '取消']
                    ,yes: function(index){


                        $.post(
                            '/admin/user/disable/'+id,
                            {},
                            function (data) {
                                if (data.code === 1){
                                    layer.msg('修改成功', function () {
                                        window.location.reload();
                                    })
                                }else{
                                    layer.msg('修改失败', {icon: 0});
                                }
                            }
                        );

                        layer.close(index);

                    }
                });
            }else{
                $.post(
                    '/admin/user/disable/'+id,
                    {

                    },
                    function (data) {
                        if (data.code === 1){
                            layer.msg('修改成功', function () {
                                window.location.reload();
                            })
                        }else{
                            layer.msg('修改失败', {icon: 0});
                        }
                    }
                );
            }




        }
    </script>
@endsection