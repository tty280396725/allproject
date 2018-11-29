<?php
namespace app\admin\controller;

use app\admin\model\Apply as ApplyClass;
use think\Db;

class Finance extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new ApplyClass();   //别名：避免与控制名冲突
    }

    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['a.uid'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get.cid')){
            $where['a.cid'] = input('get.cid');
            $this ->assign('cid',input('get.cid'));
        }
        if (input('get.oid')){
            $where['a.oid'] = input('get.oid');
            $this ->assign('oid',input('get.oid'));
        }
        if (input('get.payType')){
            $where['b.payType'] = input('get.payType');
            $this ->assign('payType',input('get.payType'));
        }

        if (in_array(input('get.dstatus'),array(0,1)) && input('get.dstatus') !== null && input('get.dstatus') !== ''){
            if (input('get.dstatus') == 1){
                $where['b.dstatus'] = ['neq',0];
            }else{
                $where['b.dstatus'] = 0;
            }
            $this ->assign('dstatus',input('get.dstatus'));
        }else{
            $where['b.dstatus'] = ['neq',0];
        }

        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'a.id asc';
        }


        $dataList = $this ->cModel ->alias('a') ->join('apply_state b','b.aid=a.id')->where($where)->order($order)->field('a.*,b.payType,b.dstatus,b.content')->paginate('', false, page_param())->each(function ($items,$key){
            $items['oname'] = model('Organization') ->getOrganization($items['oid'],array('name'))['name'];
            $items['title'] = model('Competition') ->getComtitionInfo($items['cid'],array('title'))['title'];
        });

        $orgData = model('organization')->column('name','id');
        $compData = model('competition') ->where('status',1)->column('title','id');
        $totalCost = $this->cModel->alias('a')->join('apply_state b','b.aid=a.id')->where($where)->field('a.id') ->sum('a.cost');
        $this->assign('dataList', $dataList);
        $this ->assign('compData',$compData); //竞赛数据
        $this ->assign('orgData',$orgData);
        $this ->assign('totalCost',$totalCost);
        return $this->fetch();
    }


    public function execl(){

        $where = [];
        if (input('get.search')){
            $where['a.uid'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get.cid')){
            $where['a.cid'] = input('get.cid');
            $this ->assign('cid',input('get.cid'));
        }
        if (input('get.oid')){
            $where['a.oid'] = input('get.oid');
            $this ->assign('oid',input('get.oid'));
        }
        if (input('get.payType')){
            $where['b.payType'] = input('get.payType');
            $this ->assign('payType',input('get.payType'));
        }

        if (in_array(input('get.dstatus'),array(0,1)) && input('get.dstatus') !== null && input('get.dstatus') !== ''){
            if (input('get.dstatus') == 1){
                $where['b.dstatus'] = ['neq',0];
            }else{
                $where['b.dstatus'] = 0;
            }
            $this ->assign('dstatus',input('get.dstatus'));
        }else{
            $where['b.dstatus'] = ['neq',0];
        }

        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'a.id asc';
        }

        //上面是检索的内容
        $header = array('报名ID','姓名','用户ID','性别','竞赛项目','支付方式','费用','支付状态','支付时间');
        $data =array();
        $filename = '财务数据表'.date('Y-m-d');

        $competitonData = model('Competition')->column('title','id');
        $dataList = $this->cModel->alias('a')->join('apply_state b','b.aid=a.id')->where($where)->order($order)->field('a.*,b.payType,b.dstatus')->select();

        $list =array();
        foreach ($dataList as $k=>$v){
            $list[$k][] = $v ->id;
            $list[$k][] = $v ->name;
            $list[$k][] = $v ->uid;
            $list[$k][] = parent::getnewValue($v ->sex,'sex');
            $list[$k][] = $competitonData[$v['cid']];
            $list[$k][] = $v ->payType == 1 ? '微信' : '支付宝';
            $list[$k][] = $v ->cost;
            $list[$k][] = '已支付' ;
            $list[$k][] = date('Y-m-d H:i:s',$v ->create_time);
        }

        foreach ($list as $k=>$v){
            foreach ($header as $sk => $sv){
                $data[$k][$header[$sk]] = $v[$sk];
            }
        }

        parent::importExcel($header,$data,$filename);

        die();

    }


    public function delete()
    {
        die('没有删除功能');
        if (request()->isPost()){
            $id = input('id');
            Db::startTrans();
            try{
                if (isset($id) && !empty($id)){
                    $id_arr = explode(',', $id);
                    $where = [ 'id' => ['in', $id_arr] ];
                    $where2 = ['aid'=>['in',$id_arr]];
                    $result = $this->cModel->where($where)->delete();
                    $result2 = model('apply_state') ->where($where2) ->delete();
                    if ($result && $result2){
                        Db::commit();
                        return ajaxReturn(lang('action_success'), url('index'));
                    }else{
                        return ajaxReturn($this->cModel->getError());
                    }
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ajaxReturn($e->getMessage());
            }
        }
    }




}