<?php
namespace app\admin\controller;

use app\admin\model\Feedback as FeedbackClass;
use app\admin\model\ModuleClass;

class Feedback extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new FeedbackClass();   //别名：避免与控制名冲突
    }

    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['u.username|u.phone'] = ['like', '%'.input('get.search').'%'];
        }

        if (input('get.date')){
            $create_time = strtotime(input('get.date'));
            $where['a.create_time'] = [['>', $create_time], ['<', $create_time+86400], 'and'];
        }

        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'a.id desc';
        }
        $dataList = $this->cModel ->alias('a') ->join('userinfo u','a.uid=u.id') ->where($where)->order($order)-> field('a.*,u.phone,u.username')->paginate('', false, page_param());
        foreach($dataList as &$v){
            $v['content_mini'] = mb_substr($v['content'], 0, 30).' <span style="color:green;">点击查看</span>';
            $v['content_reply_mini'] = mb_substr($v['content_reply'], 0, 30).' <span style="color:green;">点击回复</span>';
        }
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    public function edit()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->cModel->save($data, ['id'=>$data['id']]);
            return $result;
        }else{
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
}