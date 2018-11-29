<?php
namespace app\admin\controller;
use app\admin\model\RegisterUser;
use app\admin\model\Organization;

class UserinfoMechanism extends Common {
    private $userinfo;   //用户模型
    private $organization;   //机构模型

    public function _initialize() {
        parent::_initialize();
        $this->userinfo = new RegisterUser();
        $this->organization = new Organization();
    }

    //用户列表
    public function user_list(){
        $dataList = $this->userinfo->user_list();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    //用户编辑
    public function user_edit(){
        if(request()->isPost()){
            $res = $this->userinfo->user_edit();
            $res['status'] ? ajaxReturn(lang('action_success'), url('user_list')) : ajaxReturn($res['msg']);
        }else{
            $data = $this->userinfo->user_info();
            $data && $this->assign('data', $data);
            return $this->fetch();
        }
    }

    //用户删除
    public function user_del(){
        if(request()->isPost()){
            $res = $this->userinfo->user_del();
            $res ? ajaxReturn(lang('action_success'), url('user_list')) : ajaxReturn(lang('action_fail'));
        }
    }

    //机构列表
    public function mechanism_list(){
        $dataList = $this->organization->get_list();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    //机构编辑
    public function mechanism_edit(){
        if(request()->isPost()){
            $res = $this->organization->get_edit();
            $res['status'] ? ajaxReturn(lang('action_success'), url('mechanism_list')) : ajaxReturn($res['msg']);
        }else{
            $data = $this->organization->get_info();
            $data && $this->assign('data', $data);
            return $this->fetch();
        }
    }

    //机构删除
    public function mechanism_del(){
        if(request()->isPost()){
            $res = $this->organization->get_del();
            $res ? ajaxReturn(lang('action_success'), url('mechanism_list')) : ajaxReturn(lang('action_fail'));
        }
    }

}