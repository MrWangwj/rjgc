<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class Course extends Model
{

    //设置所有字段可以create创建
    protected $guarded = [];


    //


    //获取cookie 和验证码
    public static function validate($t = 0, $school='hist'){
        $data       = config('school');  //获取配置
        $base_uri   = $data['base_uri'][$school];
        $timeout    = $data['timeout'];
        $header     = $data['header'][$school]['validate'];
        $method     = $data['method'][$school]['validate'];
        $uri        = $data['uri'][$school]['validate'];

        $set = [
            'base_uri' => $base_uri,
            'timeout' => $timeout,
        ];

        $client = new Client($set);

        $jar = new CookieJar();
        $r = $client->request($method, $uri.'?t='.$t, [
            'headers' => $header,
            'cookies' => $jar,
        ]);
        session(['user_cookie' => $jar]);

        return $r->getBody();
    }


    /**
     * @param $fromdata //用户的登录信息
     * @param string $school 学校
     * @return string  登录验证信息
     */
    public static function login($fromdata, $school = 'hist'){
        $data = config('school');

        $base_url   = $data['base_uri'][$school];
        $timeout    = $data['timeout'];
        $header     = $data['header'][$school]['login'];
        $method     = $data['method'][$school]['login'];
        $uri        = $data['uri'][$school]['login'];
        $postData   = $data['formdata'][$school]['login'];


        if($school == 'hist'){
            $postData['dsdsdsdsdxcxdfgfg']      = $fromdata['password'];
            $postData['fgfggfdgtyuuyyuuckjg']   = $fromdata['validate'];
            $postData['txt_asmcdefsddsd']       = $fromdata['user_id'];

        }else{
            $postData['PassWord']      = $fromdata['password'];
            $postData['cCode']          = $fromdata['validate'];
            $postData['UserID']       = $fromdata['user_id'];
        }

        $set = [
            'base_uri' => $base_url,
            'timeout'  => $timeout,
        ];
        $client = new Client($set);
        $jar    = session('user_cookie');
        $r      = $client->request($method, $uri, [
            'headers' => $header,
            'cookies' => $jar,
            'form_params' => $postData,
        ]);

//        $myfile = fopen("/Applications/MAMP/htdocs/hist_course/test.txt", "w");
//
//        $txt = "Steve Jobs\n";
//        fwrite($myfile, $txt);
//        fwrite($myfile, $r->getBody()->getContents());
//        fclose($myfile);





        $crawler = new Crawler();
        $crawler->addHtmlContent($r->getBody(), 'gb2312');

        $text = $crawler->filter('#divLogNote>font')->text();
        return $text;
    }


    /**
     * @param $openid //微信ID
     * @param string $school //学校
     */
    public static function getCourse($openid, $school = 'hist'){

        $data       = config('school');
        $base_url   = $data['base_uri'][$school];
        $timeout    = $data['timeout'];
        $header     = $data['header'][$school]['course'];
        $method     = $data['method'][$school]['course'];
        $uri        = $data['uri'][$school]['course'];
        $jar        = session('user_cookie');

        $set = [
            'base_uri' => $base_url,
            'timeout'  => $timeout,
        ];
        $client = new Client($set);
        $course = $client->request($method, $uri, [
            'headers' => $header,
            'cookies' => $jar,
        ]);

//                $myfile = fopen("/Applications/MAMP/htdocs/hist_course/test.txt", "w");
//
//        $txt = "Steve Jobs\n";
//        fwrite($myfile, $txt);
//        fwrite($myfile, $course->getBody()->getContents());
//        fclose($myfile);

        $crawler = new Crawler();
        $crawler->addHtmlContent($course->getBody()->getContents(), 'GB2312');

        $html = '';


        $datas = [];


        // 处理html形式的课表信息，处理为数组[
        //  0=> [
        //          'course_name'=>'课程名称',
        //          'teacher' => '老师姓名'
        //          'info'  => [
        //              '[1-4周]星期一[10-11节]/0＃一',
        //              '[1-4周]星期一[10-11节]/0＃一',
        //              '[1-4周]星期一[10-11节]/0＃一',
        //          ],
        //      ]
        //  ]
        if($school == 'hist'){
            $datas = $crawler
                ->filter('.T')
                ->eq(1)
                ->nextAll()
                ->each(function (Crawler $node, $i){
                    $data       = [];
                    $tdNodes                = $node->filter('td');
                    $data['course_name']    = explode(']', $tdNodes->eq(1)->text())[1];
                    $data['teacher']        = $tdNodes->eq(4)->filter('a')->text();
                    $tmpdata           = array_filter(explode('<br>',$tdNodes->eq(10)->html()));
                    foreach ($tmpdata as $tmp){
                        $str = trim($tmp);
                        if(strpos($str, ',') != false){
                            $f = substr(explode(']', $str)[0],1, -3);
                            $l = strstr($str, ']');

                            $tmptext = '周';
                            if(substr($f, -3) == '单' || substr($f, -3) == '双'){
                                $tmptext = substr($f, -3).'周';
                                $f = substr($f, 0, -3);

                            }


                            foreach (array_filter(explode(',', $f)) as $sec){
                                $data['info'][] = '['.$sec.$tmptext.$l;
                            }

                        }else{
                            $data['info'][] = $str;
                        }
                    }
//                    $data['info']           = array_filter(explode('<br>',$tdNodes->eq(10)->html()));
                    return $data;
                });
        }else{
            $n = 0;

            foreach ($crawler->filter('.T')->eq(1)->nextAll() as $key=>$value){
                $index =  $key - $n*11;
                switch ($index){
                    case 1:{
                        $datas[$n]['course_name'] = trim(explode(']', $value->nodeValue)[1]);
                        break;
                    }
                    case 4:{
                        $datas[$n]['teacher'] = trim($value->nodeValue);
                        break;
                    }
                    case 10:{
                        $innerHTML= '';
                        $children = $value->childNodes;
                        $data = [];
                        foreach ($children as $child) {

                            $str = trim($child->nodeValue);
                            if(strpos($str, ',') != false){
                                $f = substr(explode(']', $str)[0],1, -3);
                                $l = strstr($str, ']');

                                $tmptext = '周';
                                if(substr($f, -3) == '单' || substr($f, -3) == '双'){
                                    $tmptext = substr($f, -3).'周';
                                    $f = substr($f, 0, -3);
                                }

                                foreach (array_filter(explode(',', $f)) as $sec){
                                    $data['info'][] = '['.$sec.$tmptext.$l;
                                }

                            }else{
                                $data[] = $str;
                            }

                        }
                        $datas[$n]['info'] = array_filter($data);
                        $n++;
                        break;
                    }
                }

            }


        }


//            Storage::put('course/'.$openid.'.html', implode(',',$datas), 'private');
        Cache::forever($openid.'_course',$datas);
    }

    /**
     * 分割字符串
     * @param $courses
     * @return array|bool
     */
    public static function getFrmateCourse($courses){
        $data = [];
        $weeks = ['一' => 1, '二' => 2, '三' => 3, '四' => 4, '五' => 5, '六' => 6, '七'=> 7, '日' => 7 ];
        $status = ['' => 0, '单' => 1, '双' => 2];
        $error = [];

        foreach ($courses as $course){
            $pattern = '/\[(\d{1,2}-\d{1,2}|\d{1,2})(单|双|)周\]星期(一|二|三|四|五|六|七|日)\[(\d{1,2})-(\d{1,2})节\]\/(\S*)/';
            foreach ($course['info'] as $info){
                if(! preg_match($pattern, $info, $matches)){
                    $error[] = $course['course_name'].$info;
                    continue;
                }

                $courseInfo = [];
                $courseInfo['name']     = $course['course_name'];
                $courseInfo['teacher']  = $course['teacher'];
                $section                = explode('-', $matches[1]);
                $courseInfo['week_start']    = intval($section[0]);
                $courseInfo['week_end']      = intval(isset($section[1])?$section[1]:$section[0]);
                $courseInfo['week_type']      = $status[$matches[2]];
                $courseInfo['week_day']      = $weeks[$matches[3]];
                $courseInfo['section_start'] = intval($matches[4]);
                $courseInfo['section_end'] = intval($matches[5]);
                $courseInfo['location'] = $matches[6];
                $data[] = $courseInfo;
            }
        }

        return ['data' => $data, 'error' => $error];
    }

}
