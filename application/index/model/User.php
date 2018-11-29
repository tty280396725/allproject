<?php
namespace app\index\model;

use think\Model;

class User extends Model
{
    protected $table = 'tf_userinfo';
    protected $autoWriteTimestamp = false;
}