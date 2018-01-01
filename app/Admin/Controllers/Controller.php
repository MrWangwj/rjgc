<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/1/1
 * Time: 下午4:12
 */

namespace App\Admin\Controllers;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}