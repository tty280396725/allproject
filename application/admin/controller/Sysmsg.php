<?php

namespace app\admin\controller;
use app\admin\model\Sysmsg as SysmsgModel;

class Sysmsg  extends Common{

    private $cModel;

    public function _initialize() {
        parent::_initialize();
        $this->cModel = new SysmsgModel();
    }

    public function index(){
        $dataList = $this->cModel->get_list();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isPost()){
            $r = $this->cModel->set_edit();
            $r['status'] ? ajaxReturn(lang('action_success'), url('index')) : ajaxReturn($r['msg']);
        }else{
            $this->assign('data', $this->cModel->get_info());
            return $this->fetch();
        }
    }

    public function del(){
        $res = $this->cModel->set_del();
        $res ? ajaxReturn(lang('action_success'), url('index')) : ajaxReturn(lang('action_fail'));
    }

}