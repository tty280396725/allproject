<?php
namespace app\admin\controller;

use app\admin\model\Apply as ApplyClass;
use app\admin\model\Apply_state;
use think\Db;

class Apply extends Common
{
    private $cModel;   //当前控制器关联模型
    private $AppState;

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new ApplyClass();   //别名：避免与控制名冲突
        $this ->AppState = new Apply_state();
    }

    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['a.id|a.uid'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get.cid')){
            $where['a.cid'] = input('get.cid');
            $this ->assign('cid',input('get.cid'));
        }
        if (input('get.oid')){
            $where['a.oid'] = input('get.oid');
            $this ->assign('oid',input('get.oid'));
        }

        if (in_array(input('get.dstatus'),array(0,-1,1,2)) && input('get.dstatus') !== null && input('get.dstatus') !== ''){
            $where['b.dstatus'] = input('get.dstatus');
            $this ->assign('dstatus',input('get.dstatus'));
        }

        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'a.id desc';
        }

        $compData = model('Competition') ->where('status',1)->column('title','id');

        foreach ($compData as $k=>$v){
            $idArr[] = $k;
        }

        if (!isset($where['a.cid'])){
            $where['a.cid'] = ['in',$idArr];
        }

        $dataList = $this->cModel->alias('a')
            ->join('apply_state b','b.aid=a.id')
            ->where($where)
            ->order($order)
            ->field('a.*,b.payType,b.dstatus,b.content,b.id as state_id')->paginate('', false, page_param())
            ->each(function ($items,$key){
                $items['oname'] = model('Organization')->getOrganization($items['oid'],array('name'))['name'];
                $items['title'] =  model('Competition') ->getComtitionInfo($items['cid'],array('title'))['title'];
                $items['class'] = parent::getnewValue($items['class'],'class');
            return $items;
        });

        $orgData = model('organization')->field('name,address,id') ->select();
        $compData = model('competition') ->where('status',1)->field('id,title,subject') ->select();
        $this->assign('dataList', $dataList);
        $this ->assign('compData',$compData); //竞赛数据
        $this ->assign('orgData',$orgData);
        return $this->fetch();
    }


    /***
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     * 分类导出表格
     */
    public function execl(){

        $where = [];
        if (input('get.search')){
            $where['a.id|a.uid'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get.cid')){
            $where['a.cid'] = input('get.cid');
            $this ->assign('cid',input('get.cid'));
        }

        if (input('get.oid')){
            $where['a.oid'] = input('get.oid');
            $this ->assign('oid',input('get.oid'));
        }
        if (in_array(input('get.dstatus'),array(0,-1,1,2)) && input('get.dstatus') !== null && input('get.dstatus') !== ''){
            $where['b.dstatus'] = input('get.dstatus');
            $this ->assign('dstatus',input('get.dstatus'));
        }

        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'a.id desc';
        }

        //上面是检索的内容
        $header = array('报名ID','姓名','科目','年级','性别','电话','年龄','费用','比赛项目','考试日期','考试时间','状态');
        $data =array();
        $filename = '报名数据表'.date('Y-m-d',time());

        $competitionData = model('Competition')->column('title,date,time','id');


        $dataList = $this->cModel->alias('a')->join('apply_state b','b.aid=a.id')->where($where)->order($order)->field('a.*,b.payType,b.dstatus')->select();

        $list =array();
        foreach ($dataList as $k=>$v){
            $list[$k][] = $v ->id;
            $list[$k][] = $v ->name;
            $list[$k][] = parent::getnewValue($v ->subject,'subject');
            $list[$k][] = parent::getnewValue($v ->class,'class');;
            $list[$k][] = parent::getnewValue($v ->sex,'sex');
            $list[$k][] = $v ->phone;
            $list[$k][] = $v ->age;
            $list[$k][] = $v ->cost;
            $list[$k][] = $competitionData[$v['cid']]['title'];
            $list[$k][] = date('Y-m-d',$competitionData[$v['cid']]['date']);
            $list[$k][] = $competitionData[$v['cid']][$v ->time];
            $list[$k][] = parent::getnewValue($v ->dstatus,'dstatus');
        }

        foreach ($list as $k=>$v){
            foreach ($header as $sk => $sv){
                $data[$k][$header[$sk]] = $v[$sk];
            }
        }


        parent::importExcel($header,$data,$filename);

        die();

    }


    /***
     * @return mixed|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *  在后台添加报名的功能  （已弃用）

    public function create()
    {
        if (request()->isPost()){

            Db::startTrans();
            try{
                $data = input('post.');

                if (empty($data['province']) || empty($data['city']) || empty($data['town']))
                {
                    return ajaxReturn('区域信息请填写完整');
                    die();
                }

                $data['area'] = $data['province'].'/'.$data['city'].'/'.$data['town'];
                $phone = $data['phone'];
                $userObj = new RegisterUser();
                $userData = $userObj ->where('phone',$phone)->field('id')->find(); //找该用户是否注册
                $applyData= array();
                if ($userData){
                    $data['uid'] = $userData['id'];
                    $applyData['uid'] = $userData['id'];
                }else{
                    $insertUser['phone'] = $phone;
                    $pwd = substr($phone,-6);//手机号码后6位为密码
                    $insertUser['salt'] = parent::randomkeys(4);
                    $insertUser['pwd'] = md5($pwd.'_'.$insertUser['salt']);

                    $userId = $userObj->allowField(true) ->validate(true) ->insertGetId($insertUser);

                    if (!$userId){
                        return ajaxReturn($userObj->getError());
                    }
                    $applyData['uid'] = $userId;
                    $data['uid'] = $userId;
                }


                $changeData = explode('-',$data['cid']);
                $data['cid'] = $changeData[0];

                $competObj = new Competition();//竞赛
                $data['cost'] = $competObj ->where('id',$data['cid']) ->limit(1) ->find()['cost']; //获取支付款
                $data['subject'] = $changeData[1];

                unset($data['province']);
                unset($data['city']);
                unset($data['town']);
                $insertID = $this->cModel->allowField(true)->validate(true)->insertGetId($data);

                $applyData['aid'] = $insertID;
                $applyData['create_time'] = time();

                $state = Db::table('tf_apply_state')->insert($applyData);

                if ($insertID && $state){
                    Db::commit();
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ajaxReturn($e->getMessage());
            }

        }else{
            $competObj = new Competition();//竞赛
            $organObj = new Organization();//培训机构
            $compList = $competObj ->where('status',1)->field('subject,id,title,class,cost')->select();
            $organList = $organObj ->field('name,id') ->select();
            $subjectData = Db::table('tf_subject') ->field('id,name') ->select();
            $this->assign('compList', $compList);
            $this->assign('organList',$organList);
            $this->assign('subjectData',$subjectData);
            return $this->fetch();
        }
    }
     *    */

    /***
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *  对报名信息进行修改操作
     */
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('param.');


            if (!empty($data['province']) && !empty($data['city']) && !empty($data['town']))
            {
                $data['area'] = $data['province'].'/'.$data['city'].'/'.$data['town'];
            }

            $id = $data['id'];
            $compInfo = model('Competition')->getComtitionInfo($data['cid'],array('cost'));
            $data['cost'] = $compInfo['cost']; //获取支付款

            unset($data['province']);
            unset($data['city']);
            unset($data['town']);

            $validate =  model("apply","validate");
            if (!$validate ->check($data)){
                return ajaxReturn($validate ->getError());die();
            }

            $result = $this->cModel ->where('id',$id)->update($data);

            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }

        }else{

            $compList = model('Competition') ->where('status',1)->column('subject,title','id');
            $organList = model('Organization') ->field('name,id') ->select();

            $data = $this->cModel ->where('id',$id) ->limit(1) ->find();
            $area = explode('/',$data['area']);
            $data['province'] = $area[0];
            $data['city'] = $area[1];
            $data['town'] = $area[2];
            $subjectData = \db('subject') ->field('id,name') ->select();
            $classData = \db('classes') ->column('name','id');
            $this->assign('compList', $compList);
            $this->assign('organList',$organList);
            $this->assign('subjectData',$subjectData);
            $this->assign('classData',$classData);
            $this->assign('data',$data);

            return $this->fetch();
        }
    }

    /***
     * 删除报名信息
     */
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            Db::startTrans();
            try{
                if (isset($id) && !empty($id)){
                    $id_arr = explode(',', $id);
                    $where = [ 'id' => ['in', $id_arr] ];
                    $where2 = ['aid'=>['in',$id_arr]];
                    $applyStateObj = new Apply_state();
                    $result = $this->cModel->where($where)->delete();
                    $result2 = $applyStateObj ->where($where2) ->delete();
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


    /**
     * 报名审核通过
     */
    public function check(){
        if (request()->isPost()){
            $id = input('id');

            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $applyStateObj = new Apply_state();

                $stateArr = $applyStateObj ->where('dstatus',1) ->column('id');

                $data = array();
                foreach ($id_arr as $k => $v){
                    if (in_array($v,$stateArr)){
                        $data[$k]['id'] = $v;
                        $data[$k]['dstatus'] = 2;
                    }
                }

                if ($data) {
                    $applyStateObj->isUpdate(true)->saveAll($data);
                    return ajaxReturn('审核成功', url('index'));
                }else{
                    return ajaxReturn('待审核数据为空', url('index'));
                }


            }else{
                return ajaxReturn('审核失败', url('index'));
            }


        }
    }


    /***
     * 审核拒绝
     */
    public function refue(){
        if (request()->isPost()){
            $id = input('id');
            $content = input('content');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);

                $applyStateObj = new Apply_state();

                $stateArr = $applyStateObj ->where('dstatus',1) ->column('id');

                $data = array();
                foreach ($id_arr as $k => $v){
                    if (in_array($v,$stateArr)){
                        $data[$k]['id'] = $v;
                        $data[$k]['dstatus'] = -1;
                        $data[$k]['content'] = $content;
                    }
                }

                if ($data) {
                     $applyStateObj->isUpdate(true)->saveAll($data);
                      return ajaxReturn('审核成功', url('index'));
                }else{
                    return ajaxReturn('待审核数据为空', url('index'));
                }

                }else{
                    return ajaxReturn('审核失败', url('index'));
                }
            }

    }


}