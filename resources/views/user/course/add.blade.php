@extends('user.layout.layout')

@section('title', '添加课程')

@section('css')
    <style>
        .select-double>select{
            float: left;
            width: 50%;
        }
    </style>
@endsection

@section('content')
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">名称</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入课程名称"  id="name">
            </div>

        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">教师</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入授课教师"  id="teacher">
            </div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">地点</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入上课地点"  id="location">
            </div>
        </div>

        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">星期</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select" id="week_day">
                    <option value="1">一</option>
                    <option value="2">二</option>
                    <option value="3">三</option>
                    <option value="4">四</option>
                    <option value="5">五</option>
                    <option value="6">六</option>
                    <option value="7">日</option>
                </select>
            </div>
        </div>


        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">节数</label>
            </div>
            <div class="weui-cell__bd select-double">
                <select class="weui-select" id="section_start">

                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }}节</option>
                    @endfor
                </select>

                <select class="weui-select" id="section_end" >
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }}节</option>
                    @endfor
                </select>
            </div>
        </div>




        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">周数</label>
            </div>
            <div class="weui-cell__bd select-double">
                <select class="weui-select" id="week_start">
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}">{{ $i }}周</option>
                    @endfor
                </select>
                <select class="weui-select" id="week_end">
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}">{{ $i }}周</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">类型</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select" id="week_type">
                    <option value="0">全部</option>
                    <option value="1">单周</option>
                    <option value="2">双周</option>
                </select>
            </div>
        </div>


    </div>


    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="" onclick="addCourse()">添加</a>
    </div>
@endsection

@section('js')
    <script>
        function addCourse(){
            $.showLoading('数据加载中..');

            let name = $('#name').val(),
                teacher = $('#teacher').val(),
                location = $('#location').val(),
                section_start = $('#section_start').val(),
                section_end = $('#section_end').val(),
                week_start = $('#week_start').val(),
                week_end = $('#week_end').val(),
                week_type = $('#week_type').val(),
                week_day = $('#week_day').val();

            $.post(
                '/user/course/course/add',
                {
                    name: name,
                    teacher: teacher,
                    location: location,
                    section_start: section_start,
                    section_end: section_end,
                    week_start: week_start,
                    week_end: week_end,
                    week_type: week_type,
                    week_day: week_day,
                },
                function (data) {
                    $.hideLoading();
                    if(data.code === 1){
                        $.toast(data.msg, function () {
                            window.location = '/user/course/course/my';
                        });
                    }else{
                        $.toast(data.msg);
                    }
                }
            );
        }
    </script>
@endsection