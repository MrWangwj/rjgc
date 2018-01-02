<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{

    //用户课表
    public function userCourse(){
        $openid =session('wechat.oauth_user')->id;;
        $user = User::getUserByOpenId($openid);


        $user->load('courses');
        $startSchool = 1504454400;
        return view('user.course.course', compact(['user','startSchool']));
    }


    //教务系统验证码
    public function eduLoginValidate($t){
        return Course::validate($t);
    }


    //教务系统登录
    public function eduLogin(){
        $this->validate(request(), [
            'user_id' => 'required',
            'password' => 'required',
            'validate' =>'required',
        ]);
        $openid =session('wechat.oauth_user')->id;


        //登录，获取登录状态
        $text = Course::login(\request());

        if($text != '正在加载权限数据...') return ['code' => 0, 'msg' => $text];
        else{
            Course::getCourse($openid);
            return ['code' => 1, 'msg' => '登录成功'];
        }
    }


    //课程页面渲染
    public function inputCourse(){
        $openid =session('wechat.oauth_user')->id;
        //判断是否有缓存
        if(!Cache::has($openid.'_course'))
            return redirect('/user/course/edu/login');
        $courses = Cache::get($openid.'_course');
        return view('user.course.input', compact('courses'));
    }

    //导入课表
    public function setInput(){

        $openid =session('wechat.oauth_user')->id;


        //分割字符串成为课表数据
        $result = Course::getFrmateCourse(Cache::get($openid.'_course'));

        if(count($result['error']) > 0){
            return ['code' => 0, 'msg' => $result['error'][0]];
        }


        $courses = $result['data'];


        //创建课表信息
        $course_id = [];
        foreach ($courses as $course) {
            $course_id[] = (Course::firstOrCreate($course))->id;
        }

        //保存用户的课表

        $user = User::getUserByOpenId($openid);

        $user->courses()->sync($course_id);

        //清除缓存
        Cache::forget($openid.'_course');
        return ['code' => '1', 'msg' => '导入成功'];
    }


    //删除课程
    public function delete(){
        $this->validate(\request(),[
            'id' => 'required'
        ]);

        $openid =session('wechat.oauth_user')->id;

        $user = User::getUserByOpenId($openid);
        $user->courses()->detach(\request('id'));

        return ['code' => 1, 'msg' => '删除成功'];
    }

    //添加课程
    public function setAdd(){
        //验证
        $this->validate(\request(), [
            'name' => 'required|max:150',
            'teacher' => 'required|max:45',
            'location' => 'required|max:150',
            'week_start' => 'required|Integer|between:1,20',
            'week_end' => 'required|Integer|between:1,20',
            'section_start' => 'required|Integer|between:1,12',
            'section_end' => 'required|Integer|between:1,12',
            'week_day' => 'required|Integer|between:1,7',
            'week_type' => [
                'required',
                Rule::in([0, 1, 2]),
            ]
        ]);
        $request = request();
        if($request['week_start'] > $request['week_end'] || $request['section_start'] > $request['section_end']){
            return ['code' => 0, 'msg' => '错误操作'];
        }



        $openid =session('wechat.oauth_user')->id;
        $user = User::getUserByOpenId($openid);




        $week_day_course = $user->courses->where('week_day', $request['week_day']);

        $result = $week_day_course->filter(function ($value, $key) use($request){

            if($value->week_type ==0 || $value->week_type = $request['week_type']){
                if(max($request['section_start'], $value->section_start)
                    <= min($request['section_end'], $value->section_end)){
                    if(max($request['week_start'], $value->week_start)
                        <= min($request['week_end'], $value->week_end)){
                        return true;
                    }

                }
            }
        });


        if($result->count() > 0){
            return ['code' => 0, 'msg' => '课程冲突'.$result->first()->name];
        }

        //存储
        $course = Course::firstOrCreate(\request()->all());
        if(!$course){
            return ['code' => 1, 'msg' => '添加失败'];
        }
        $user->courses()->attach($course->id);


        return ['code' => 1, 'msg' => '添加成功'];
        //返回
    }


    //编辑课程页面渲染
    public function getEdit(Course $course){
        return view('user.course.edit', compact('course'));
    }


    //编辑
    public function setEdit(){
        //验证
        $this->validate(\request(), [
            'id' => 'required|numeric|exists:courses,id',
            'name' => 'required|max:150',
            'teacher' => 'required|max:45',
            'location' => 'required|max:150',
            'week_start' => 'required|Integer|between:1,20',
            'week_end' => 'required|Integer|between:1,20',
            'section_start' => 'required|Integer|between:1,12',
            'section_end' => 'required|Integer|between:1,12',
            'week_day' => 'required|Integer|between:1,7',
            'week_type' => [
                'required',
                Rule::in([0, 1, 2]),
            ]
        ]);
        $request = request();
        if($request['week_start'] > $request['week_end'] || $request['section_start'] > $request['section_end']){
            return ['code' => 0, 'msg' => '错误操作'];
        }


        $openid =session('wechat.oauth_user')->id;
        $user = User::getUserByOpenId($openid);





        $week_day_course = $user->courses->where('week_day', $request['week_day'])->where('id', '!=', $request['id']);

        $result = $week_day_course->filter(function ($value, $key) use($request){

            if($value->week_type ==0 || $value->week_type = $request['week_type']){
                if(max($request['section_start'], $value->section_start)
                    <= min($request['section_end'], $value->section_end)){
                    if(max($request['week_start'], $value->week_start)
                        <= min($request['week_end'], $value->week_end)){
                        return true;
                    }

                }
            }
        });


        if($result->count() > 0){
            return ['code' => 0, 'msg' => '课程冲突'.$result->first()->name];
        }


        //存储
        $course = (Course::firstOrCreate(request([
            'name',
            'teacher',
            'location',
            'week_start',
            'week_end',
            'section_start',
            'section_end',
            'week_day',
            'week_type'
        ])));
        if(!$course){
            return ['code' => 0, 'msg' => ' 修改失败'];
        }

        if($course->id != $request['id']){
            $user->courses()->detach($request['id']);
            $user->courses()->attach($course->id);
        }

        return ['code' => 1, 'msg' => '修改成功'];
        //返回
    }
}
