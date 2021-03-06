<?php
namespace app\admin\controller;

use app\admin\model\Ticket;
use think\Db;

class Need extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Ticket();   //别名：避免与控制名冲突
    }

    /***
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 待考试的列表
     */

    public function need(){

        if (request() ->isGet()){
            $where = [];
            if (input('get.search')){
                $where['aid|uid'] = ['like', '%'.input('get.search').'%'];
            }

            if (input('get.cid')){
                $where['cid'] = input('get.cid');
                $this ->assign('cid',input('get.cid'));
            }
            if (input('get.subject')){
                $where['subject'] = input('get.subject');
                $this ->assign('subject',input('get.subject'));
            }

            if (input('get.class')){
                $where['class'] = input('get.class');
                $this ->assign('class',input('get.class'));
            }

            if (input('get._sort')){
                $order = explode(',', input('get._sort'));
                $order = $order[0].' '.$order[1];
            }else{
                $order = 'cid desc';
            }

            $compData = model('Competition') ->where('status',1)->where('subject','neq',5)->column('title,subject','id');
            foreach ($compData as $k=>$v){
                $idArr[] = $k;
            }
            if (!isset($where['a.cid'])){
                $where['cid'] = ['in',$idArr];
            }

            $dataList = $this->cModel
                    ->where($where)
                    ->order($order)
                    ->field('*')
                    ->paginate('', false, page_param())
                    ->each(function ($item,$key){
                           $item['class'] = parent::getnewValue($item['class'],'class');
                    });

            $classData = \db('classes') ->field('id,name') ->select();
            $this->assign('dataList', $dataList);
            $this ->assign('compData',$compData); //竞赛数据
            $this ->assign('classData',$classData);

            return $this->fetch('need');
        }

    }

    /***
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 上传考试用户的准考证和考试地点
     */
    public function importExecl(){

        if (request()->isPost()) {

            $header = array('id','name','class','uid','title','date','time','addr','tnum');

            $resultRe = parent::exportExcel($header);
            if (empty($resultRe)){
                $this ->error('上传格式错误');
                die();
            }

            $resultRs = parent::array_unset_tt($resultRe,'id'); //二维数组根据ID值来去重

            foreach ($resultRs as $v){
                $result[] = $v;
            }

            $ticketData =  $this ->cModel ->column('id','aid');

            $tidArr =array();
            $aidArr = array();
            foreach ($ticketData as $k=>$v){
                $tidArr[$v['aid']] = $v;
                $aidArr[] = $k;
            }

            $competionData = model('Competition') ->column('title,date,time','id');

            $applyObj = new \app\admin\model\Apply();
            $error = array();
            $insert = array();
            $idsArr = array();
            $update = array();

            foreach ($result as $k=>$v){
                $id = intval($v['id']);
                $addr = trim($v['addr']);
                $tnum = trim($v['tnum']);
                if ($id && $addr && $tnum){
                    $data = $applyObj->where('id',$id)->field('cid,subject,age,class,name,sex,pic,uid,id')->find();
                    if ($data){
                        if (in_array($id,$aidArr)){
                            /**执行修改分支*/
                            $update[$k]['tnum'] = $tnum;
                            $update[$k]['addr'] = $addr;
                            $update[$k]['update_time'] = time();
                            $update[$k]['id'] = $tidArr[$data['id']];
                        }else{
                            $idsArr[$k]['id'] = $data['id'];
                            $idsArr[$k]['is_create'] = 1;

                            $insert[$k]['aid'] = $data['id'];
                            $insert[$k]['tnum'] = $tnum;
                            $insert[$k]['uid'] = $data['uid'];
                            $insert[$k]['cid'] = $data['cid'];
                            $insert[$k]['addr'] = $addr;
                            $insert[$k]['name'] = $data['name'];
                            $insert[$k]['sex'] = $data['sex'];
                            $insert[$k]['age'] = $data['age'];
                            $insert[$k]['subject'] = $data['subject'];
                            $insert[$k]['class'] = $data['class'];
                            $insert[$k]['time'] = $competionData[$data['cid']]['time'];
                            $insert[$k]['date'] = $competionData[$data['cid']]['date'];
                            $insert[$k]['pic'] = $data['pic'];
                            $insert[$k]['title'] = $competionData[$data['cid']]['title'];
                            $insert[$k]['create_time'] = time();
                        }
                    }else{
                        $error[$k]['name'] = $v['name'];
                        $error[$k]['tnum'] = $v['tnum'];
                    }
                }

            }

            //var_dump($insert);var_dump($update);var_dump($idsArr);die();

            if (empty($insert) && empty($update)){
                $this ->error('上传内容不能为空,或者准考证号和考点为空');die();
            }

            if (empty($insert) && $update){
                $res3 = $this ->cModel ->isUpdate(true)->saveAll($update); //批量修改ticket的数据
                if ($res3){
                    return $this->success('上传成功');
                }else{
                    return $this ->error('上传失败');
                }
            }


            if (empty($update) && $insert){

                $this ->cModel ->saveAll($insert,false);
                $applyObj ->isUpdate(true)->saveAll($idsArr); //批量更新apply表的is_create 字段

                return $this->success('上传成功');
            }

            if ($insert && $update){

                $this ->cModel ->saveAll($insert);
                $applyObj ->isUpdate(true)->saveAll($idsArr); //批量更新apply表的is_create 字段
                $this ->cModel ->isUpdate(true)->saveAll($update); //批量修改ticket的数据

                return $this->success('上传成功');
            }

        }

    }


    /***
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 删除准考证
     */

    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];

                $applyObj = new \app\admin\model\Apply();

                $ticketData = model('Ticket') ->where($where) ->field('aid') ->select(); //去掉书画的不需要修改

                $applyList = array();
                foreach ($ticketData as $k=>$v){
                    $applyList[$k]['id'] = $v['aid'];
                    $applyList[$k]['is_create'] = 0;
                    $applyList[$k]['update_time'] = time();
                }

                Db::startTrans();
                try{
                    $result = $this->cModel->where($where)->delete();
                    if ($result){
                        $applyObj ->isUpdate(true) ->saveAll($applyList);
                        Db::commit();
                        return ajaxReturn('操作成功', url('need'));
                    }else{
                        return ajaxReturn($this->cModel->getError());
                    }

                }catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return ajaxReturn($e->getMessage());
                }

            }

        }
    }

    /****
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 下载考试模板
     */
    public function download(){

        $where = [];
        $etc = '';
        if (input('get.cid')){
            $where['a.cid'] = input('get.cid');
            $this ->assign('cid',input('get.cid'));
            $etc = model('Competition')->getComtitionInfo(input('get.cid'),array('title'))['title'];
        }

        $competitionData = model('Competition') ->where('id',intval(input('get.cid')))->field('title,date,time') ->find();

        $applyObj = new \app\admin\model\Apply();

        $dataList = $applyObj ->alias('a') ->join('apply_state b','b.aid=a.id')->where($where)->where('b.dstatus',2)->order('a.id desc')->field('a.*,b.payType,b.dstatus')->select();

        if ($dataList){
            $list = array();
            foreach ($dataList as $k=>$v){
                $list[$k][] = $v->id;
                $list[$k][] = $v->name;
                $list[$k][] = parent::getnewValue($v ->subject,'subject');
                $list[$k][] = $v->uid;
                $list[$k][] = $competitionData['title'];
                $list[$k][] = date('Y-m-d',$competitionData['date']);
                $list[$k][] = $competitionData['time'];
                $list[$k][] = '';
                $list[$k][] = '';
            }
        }else{
            $list =array();
        }


        $header = array('报名ID','姓名','年级','用户ID','比赛项目','考试日期','考试时间','考试地点','准考证号');
        $data =array();
        $filename = $etc.'考试地点填写模板'.date('Y-m-d');

        foreach ($list as $k=>$v){
            foreach ($header as $sk => $sv){
                $data[$k][$header[$sk]] = $v[$sk];
            }
        }

        parent::importExcel($header,$data,$filename);
        die();
    }


}