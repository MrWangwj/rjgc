@extends('user.layout.layout')

@section('title', '编辑课程')

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
                <input class="weui-input" type="text" placeholder="请输入课程名称" value="{{ $course->name }}" id="name">
            </div>

        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">教师</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入授课教师" value="{{ $course->teacher }}" id="teacher">
            </div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">地点</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" placeholder="请输入上课地点" value="{{ $course->location }}" id="location">
            </div>
        </div>

        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">星期</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select" id="week_day" value="{{ $course->week_day }}">
                    <option value="1" @if(1 == $course->week_day) selected="selected" @endif>一</option>
                    <option value="2" @if(2 == $course->week_day) selected="selected" @endif>二</option>
                    <option value="3" @if(3 == $course->week_day) selected="selected" @endif>三</option>
                    <option value="4" @if(4 == $course->week_day) selected="selected" @endif>四</option>
                    <option value="5" @if(5 == $course->week_day) selected="selected" @endif>五</option>
                    <option value="6" @if(6 == $course->week_day) selected="selected" @endif>六</option>
                    <option value="7" @if(7 == $course->week_day) selected="selected" @endif>日</option>
                </select>
            </div>
        </div>


        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">节数</label>
            </div>
            <div class="weui-cell__bd select-double">
                <select class="weui-select" id="section_start" value="{{ $course->section_start }}">

                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" @if($i == $course->section_start) selected="selected" @endif>{{ $i }}节</option>
                    @endfor
                </select>

                <select class="weui-select" id="section_end" value="{{ $course->section_end }}">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" @if($i == $course->section_end) selected="selected" @endif>{{ $i }}节</option>
                    @endfor
                </select>
            </div>
        </div>




        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">周数</label>
            </div>
            <div class="weui-cell__bd select-double">
                <select class="weui-select" id="week_start" value="{{ $course->week_start }}">
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" @if($i == $course->week_start) selected="selected" @endif>{{ $i }}周</option>
                    @endfor
                </select>
                <select class="weui-select" id="week_end" value="{{ $course->week_end }}">
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" @if($i == $course->week_end) selected="selected" @endif>{{ $i }}周</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="weui-cell weui-cell_select weui-cell_select-after ">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">类型</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select" id="week_type" value="{{ $course->week_type }}">
                    <option value="0" @if(0 == $course->week_type) selected="selected" @endif>全部</option>
                    <option value="1" @if(1 == $course->week_type) selected="selected" @endif>单周</option>
                    <option value="2" @if(2 == $course->week_type) selected="selected" @endif>双周</option>
                </select>
            </div>
        </div>


    </div>


    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="" onclick="saveCourse()">保存</a>
    </div>
@endsection

@section('js')
    <script>
        function saveCourse(){
            $.showLoading('数据加载中..');

            let id = '{{ $course->id }}',
                name = $('#name').val(),
                teacher = $('#teacher').val(),
                location = $('#location').val(),
                section_start = $('#section_start').val(),
                section_end = $('#section_end').val(),
                week_start = $('#week_start').val(),
                week_end = $('#week_end').val(),
                week_type = $('#week_type').val(),
                week_day = $('#week_day').val();

            $.post(
                '/user/course/course/edit',
                {
                    id: id,
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