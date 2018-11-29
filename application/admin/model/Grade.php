<?php
namespace app\admin\model;

use think\Model;

class Grade extends Model{

    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    public function getisPassAttr($val){
        $arr = [-1=>'不合格', 1=>'合格'];
        return $arr[$val];
    }

}