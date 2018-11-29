<?php
namespace app\admin\controller;


use app\admin\model\Competition as CompetionClass;
/**
 * Class Competition
 * @package app\admin\controller
 * 竞赛信息
 */
class Competition extends Common{

    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new CompetionClass;   //别名：避免与控制名冲突
    }

    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['title'] = ['like', '%'.trim(input('get.search')).'%'];
        }
        input('sid') && $where['subject'] = input('sid');
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'id desc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        $subject = db('subject')->column('id,name,is_level');
        $show_level_ids = []; //只包含带阶段比赛的学科id
        foreach($subject as $v){
            if($v['is_level']){
                $show_level_ids[] = $v['id'];
            }
        }
        $this->assign('dataList', $dataList);
        $this->assign('subject', $subject);//所有学科分类
        $this->assign('show_level_ids', $show_level_ids);//只包含带阶段比赛的学科id
        return $this->fetch();
    }

    public function create()
    {
        if (request()->isPost()){
            $data = input('post.');
            $data['time'] = $data['stime'].'-'.$data['etime'];
            $result = $this->cModel->validate(true)->allowField(true)->save($data);
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $this->assign('subject', db('subject')->select()); //学科分类
            $this->assign('classes', db('classes')->column('id,name')); //年级分类
            return $this->fetch();
        }
    }

    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            $data['time'] = $data['stime'].'-'.$data['etime'];
            $result = $this->cModel->validate(true)->allowField(true)->save($data, $data['id']);
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->where(['id'=>$id])->find();
            $time = explode('-', $data->time);
            $data->stime = $time[0];
            $data->etime = $time[1];
            $this->assign('data', $data);
            $this->assign('subject', db('subject')->select()); //学科分类
            $this->assign('classes', db('classes')->column('id,name')); //年级分类
            return $this->fetch('create');
        }
    }

    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }
    }

    // 生成下阶段考试
    public function next_competition(){
        $conf = db('config')->where(['k'=>'competition_level'])->value('v'); //获取竞赛阶段配置
        $conf_len = count(explode('*', $conf))-1; //有几个阶段
        $data = input();
        $r = $this->cModel->save(['reload'=>1], ['id'=>$data['id']]);
        if($r){
            $info = $this->cModel->where(['id'=>$data['id']])->find();
            $save_arr = $info->toArray();
            $save_arr['pid'] = $save_arr['id'];
            unset($save_arr['id']);
            $save_arr['status'] = 0;
            if($info->typeData < $conf_len){
                $save_arr['type'] = $info->typeData+1;
                // 如果到了最后阶段
                if($save_arr['type'] < $conf_len){
                    $save_arr['reload'] = 0;
                }
            }else{
                return 0;
            }
            $time = time();
            $save_arr['create_time'] = $time;
            $save_arr['update_time'] = $time;

            $save_obj = CompetionClass::create($save_arr);
            $save_arr = $save_obj->toArray();
            return 1;
        }else{
            return 0;
        }
    }

}