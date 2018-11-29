<?php
namespace app\admin\validate;


class Competition extends \think\Validate {
    protected $rule = [
        'title|标题'  =>  'require|max:100',
        'subject|学科' =>  'require|max:50',
        'class|年级' =>  'require|max:30',
        'content|详情' =>  'require',
        'pass_line|合格分数' =>  'require|gt:0',
        'startTime|申报开始时间' =>  'require',
        'endTime|申报结束时间' =>  'require',
        'date|考试日期' =>  'require',
        'time|考试时间段' =>  'require',
        'cost|费用' =>  'require|gt:0',
        'com_des|赛事描述' =>  'require',
    ];
}