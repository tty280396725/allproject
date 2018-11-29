<?php
namespace app\admin\validate;

use think\Validate;

class Apply extends Validate
{
    protected $rule = [
        ['name','require|max:30','名称必须|名称不能超过30个字符'],
        ['sex','require|[0,1]','性别必填|请选择性别'],
        ['area','require','地区必须'],
        ['addr','require','地址必填'],
        ['phone','require|max:11|/^1[3-8]{1}[0-9]{9}$/','手机号码必填|手机号码格式错误|手机号码格式错误'],
        ['pic','require','照片必须'],
    ];

}