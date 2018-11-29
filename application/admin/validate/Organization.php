<?php
namespace app\admin\validate;

class Organization extends \think\Validate {

    protected $rule = [
        'name|名称'  =>  'require|max:50',
    ];

}