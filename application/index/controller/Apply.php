<?php
namespace app\index\controller;

use app\admin\model\Apply_state;
use think\Cookie;
use think\Db;

class Apply extends Common{

    public $user_id;

    public function _initialize(){
        parent::_initialize();
    }

    /***
     * @return \think\response\Json
     * 用户提交报名
     */
    public function applicant(){

       if (request() ->isPost()){
           $data = input('param.');
           $err = "";
           !trim($data['cid']) && $err = '请选择竞赛项目';
           !trim($data['subject']) && $err = '请选择科目信息';
           !trim($data['name']) && $err = '请输入申报姓名';
           !trim($data['oid']) && $err = '请选择培训机构';
           !trim($data['area']) && $err = '请选择地区信息';
           !trim($data['phone']) && $err = '请输入电话号码';
           !trim($data['pname']) && $err = '请输入父母一方姓名';
           !trim($data['pic']) && $err = '请上传照片';
           !trim($data['addr']) && $err = '请输入详细地址';
           if($err){
               return parent::_responseResult(0, $err);die();
           }

           $user_id = $this ->user_id;
           $key = 'user_id_pay_data_'.$user_id;

           Cookie::set($key,$data,3600);
           //正常状态走这
           //if(Cookie::has($key)){
                Cookie::set('user_id_cid'.$user_id,$data['cid'],3600); //判断支付的竞赛的id是否和传的一致记录报名的cid
               //return parent::_responseResult('1','返回正常');
           //}else{
               //return parent::_responseResult('0','返回异常');
           //}

           $applyStateObj = new Apply_state();
           /***开启报名测试的功能***/
           $data['uid'] = $user_id;
           Db::startTrans();
           try{
               $insertId = model('Apply') ->allowField(true) ->insertGetId($data);
               $insert['aid'] = $insertId;
               $insert['uid'] = $user_id;
               $insert['dstatus'] = 1;

               $res2 = $applyStateObj ->allowField(true) ->save($insert);

               if ($insertId && $res2){
                   Db::commit();
                   return parent::_responseResult('1','报名成功');
               }
           }catch (\Exception $e) {
               // 回滚事务
               Db::rollback();
               return parent::_responseResult('0','报名失败');
           }

       }
    }


    /***
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 修改报名信息
     */
    public function reloadInfo(){

        if (request() ->isPost()){

            $data = input('param.');
            $id = intval($data['aid']);
            unset($data['aid']);
            $err = "";
            !trim($data['cid']) && $err = '请选择竞赛项目';
            !trim($data['subject']) && $err = '请选择科目信息';
            !trim($data['name']) && $err = '请输入申报姓名';
            !trim($data['oid']) && $err = '请选择培训机构';
            !trim($data['area']) && $err = '请选择地区信息';
            !trim($data['phone']) && $err = '请输入电话号码';
            !trim($data['pname']) && $err = '请输入父母一方姓名';
            !trim($data['pic']) && $err = '请上传照片';
            !trim($data['addr']) && $err = '请输入详细地址';
            if($err){
                return parent::_responseResult(0, $err);die();
            }
            $data['pic'] = strstr($data['pic'], '/uploads');
            $user_id = $this ->user_id;
            $applyStateObj = new Apply_state();
            /***开启报名测试的功能***/
            $data['uid'] = $user_id;
            $res = $applyStateObj ->allowField(true)->where('id',$id) ->update($data);
            if ($res){
                return parent::_responseResult('1','修改成功');
            }else{
                return parent::_responseResult('0','修改失败');
            }

        }

    }

    /***
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 用户报名的支付页面
     */
    public function is_apply(){

        if (request() ->isGet()){
            $data = input('get.');
            $cid = trim($data['cid']);  //用不到
            $user_id = $this ->user_id;
            $apply_cid = Cookie::get('user_id_cid'.$user_id); //记录的报名时候的报名的项目id值
            $compData = array();

            if ($apply_cid){
                $compData = model('Competition') ->where('id',$apply_cid) ->field('date,title,time,cost,class,subject') ->find();
                $compData['date'] = date('Y-m-d',$compData['date']);
                $compData['subject'] = parent::getnewValue($compData['subject'],'subject');
            }

            return parent::_responseResult('1','请求成功',$compData);
        }
    }

   public function test(){

       $res = Cookie::set('ss',123);

       var_dump($res);die();
   }


    /***
     * @return \think\response\Json
     * 支付成功后的回调的接口   尚未完成更新支付的方式
     */

    public function pay_callback_common(){

        $user_id = $this ->user_id;

        $apply_data = Cookie::get('user_id_pay_data_'.$user_id); //报名是的数据

        $apply_data['uid'] = $user_id;

        $insertId = model('Apply') ->allowField(true) ->insertGetId($apply_data);

        $insert['aid'] = $insertId;
        $insert['uid'] = $user_id;
        $insert['dstatus'] = 1;

        $applyStateObj = new Apply_state();
        $applyStateObj ->allowField(true) ->save($insert);

        return parent::_responseResult('1','数据更新成功');


        /**
        Db::startTrans();
        try{
            $insertId = model('Apply') ->allowField(true) ->insertGetId($apply_data);
            $insert['aid'] = $insertId;
            $insert['uid'] = $user_id;

            $res2 = model('Apply_state') ->allowField(true) ->save($insert);
            if ($insertId && $res2){
                Db::commit();
                return parent::_responseResult('1','报名成功');
            }

        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return parent::_responseResult('0','报名失败');
        }**/



    }


}