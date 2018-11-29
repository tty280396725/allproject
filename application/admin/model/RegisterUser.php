<?php
namespace app\admin\model;

class RegisterUser extends \think\Model {

    protected static $param;   //获取提交参数

    protected $table = 'tf_userinfo';
    protected $autoWriteTimestamp = false;


    protected static function init(){
        self::$param = input();
    }

    public function getSexAttr($val){
        $arr = [1=>'男', 2=>'女'];
        return $arr[$val];
    }
    public function getLastLoginAttr($val){
        return date('Y/m/d H:i', $val);
    }

    // 获取用户列表
    public function user_list(){
        $where = [];
        $search = trim(self::$param['search']);
        !empty($search) && $where['username|phone'] = ['LIKE', '%'.$search.'%'];
        if(!empty(self::$param['_sort'])){
            $order = explode(',', self::$param['_sort']);
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'id desc';
        }
        return $this->where($where)->order($order)->paginate('', false, page_param());
    }

    // 获取用户信息
    public function user_info(){
        if(self::$param['id']){
            $where['id'] = self::$param['id'];
            $data = $this->where($where)->find();
        }
        return $data;
    }

    // 编辑用户信息
    public function user_edit(){
        $user_info = $this->user_info();
        if($user_info){
            $res = $user_info->validate(true)->save(self::$param);
            $msg = $user_info->getError();
        }else{
            $res = $this->validate(true)->save(self::$param);
            $msg = '';
        }
        return ['status'=>$res, 'msg'=>$msg];
    }

    // 删除用户
    public function user_del(){
        $ids = explode(',', self::$param['id']);
        $res = $this->where(['id'=>['IN', $ids]])->delete();
        return $res;
    }

}