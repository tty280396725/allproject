<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
session_start();
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('EXTEND_PATH', __DIR__ .'/../extend/');//手动扩展extend的文件
define('WEB_PATH', __DIR__);
define('APP_DEBUG', true);

header("Access-Control-Allow-Origin:http://192.168.10.50:8087");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers:Content-Type");
header("Access-Control-Allow-Credentials:true");
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    exit;
}

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
