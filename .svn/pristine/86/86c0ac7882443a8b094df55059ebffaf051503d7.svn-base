<?php

namespace app\admin\model;


class Sysmsg extends \think\Model{

    private static $param;

    public static function init() {
        parent::init();
        self::$param = input();
    }

    public function getUsernameAttr($val){
        !$val && $val='所有用户';
        return $val;
    }

    public function get_list(){
        $data = self::$param;
        $where = [];
        if(isset($data['search'])){
            $search = trim($data['search']);
            $search && $where['s.title'] = ['LIKE', '%'.trim($data['search']).'%'];
        }
        if(isset($data['date'])){
            $date_time = strtotime($data['date']);
            $date_time && $where['s.create_time'] = [['>', $date_time], ['<', $date_time+86400], 'and'];
        }
        if ($data['_sort']){
            $order = explode(',', $data['_sort']);
            $order = $order[0].' '.$order[1];
        }else{
            $order = 's.id desc';
        }
        $dataList = $this->alias('s')->join('tf_userinfo u', 'u.id=s.uid', 'left')->field('s.*, u.username, u.phone')->where($where)->order($order)->paginate('', false, page_param());
        foreach ($dataList as &$v){
            $v['title'] = mb_substr($v['title'], 0, 20);
            $v['content'] = mb_substr($v['content'], 0, 30);
        }
        return $dataList;
    }

    public function get_info(){
        if(self::$param['id']){
            $info = $this->alias('s')->join('tf_userinfo u', 'u.id=s.uid', 'left')->field('s.*, u.username, u.phone')->where(['s.id'=>self::$param['id']])->find();
            return $info;
        }elseif(self::$param['uid']){
            $info = db('userinfo')->where(['id'=>self::$param['uid']])->field('id as uid, username, phone')->find();
            return $info;
        }
    }

    public function set_edit(){
        $data = self::$param;
        if($data['id']){
            $res = $this->validate(true)->allowField(true)->save($data, ['id'=>$data['id']]);
        }else{
            $res = $this->validate(true)->allowField(true)->save($data);
        }
        return ['status'=>$res, 'msg'=>$this->getError()];
    }

    public function set_del(){
        $ids = explode(',', self::$param['id']);
        $res = $this->where(['id'=>['IN', $ids]])->delete();
        return $res;
    }

}