<?php
namespace app\index\controller;

use app\index\model\User as UserClass;
use think\Cache;

use think\Db;
use think\Request;
use think\Session;


class User extends Common
{
    public function _initialize(){
        parent::_initialize();
    }

    /***
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 用户使用密码登录
     */
    public function login(Request $request){

        if ($request ->isPost()){
            $data = input('post.');
            $phone = $data['phone'];
            $userObj = new UserClass();

            $userData = $userObj ->where('phone',$phone) ->field('id,pwd,salt,status') ->find();
            if (!$userData){
                return parent::_responseResult('0','该手机用户不存在');die();
            }

            $pwd = $userData['pwd'];
            $repwd = md5($data['pwd'].'_'.$data['salt']);
            $userData = $userData['status'];
            $id = $userData['id'];

            if ($pwd === $repwd){
                if ($userData['status'] == -1){
                    return parent::_responseResult('0','该账号已被拉黑');die();
                }

            }else{
                return parent::_responseResult('0','密码错误');die();
            }

            $loginData['login_time'] = time();
            $loginData['ip'] = $request ->ip();

            $result = $userObj ->where('id',$id)->limit(1) ->update($loginData);

            if ($result){
                session('user_id', $id);
                return parent::_responseResult('1','登录成功');die();
            }else{
                return parent::_responseResult('0','登录失败');die();
            }

        }

    }

    /****
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 用户进行手机获取短信登录
     */
    public function loginVerify(Request $request){
        if ($request ->isPost()){
            $data = input('input.');
            $phone = trim($data['phone']);
            $code = trim($data['code']);

            $userObj = new UserClass();

            $userData = $userObj ->where('phone',$phone) ->field('id,status') ->find();
            if (!$userData){
                return parent::_responseResult('0','该手机用户不存在');die();
            }

            $smsData = Db::table('tf_smscode') ->where('phone',$phone) ->where('code',$code) ->order('id desc')->field('code') ->limit(1) ->find();

            if (!$smsData){
                return parent::_responseResult('0','验证码不存在');die();
            }

            if ($code !== $smsData['code']){
                return parent::_responseResult('0','验证码输入有误');die();
            }


            $userData = $userData['status'];
            $id = $userData['id'];

            if ($userData['status'] == -1){
                return parent::_responseResult('0','该账号已被拉黑');die();
            }

            $loginData['login_time'] = time();
            $loginData['ip'] = $request ->ip();

            $result = $userObj ->where('id',$id)->limit(1) ->update($loginData);

            if ($result){
                session('user_id_', $id);
                return parent::_responseResult('1','登录成功');die();
            }else{
                return parent::_responseResult('0','登录失败');die();
            }

        }
    }



    /***
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 用户修改密码
     */
    public function reloadPwd(Request $request){

        if ($request ->isPost()){
            $data = input('input.');
            $insertData = array();
            $code= trim($data['code']);
            $phone = trim($data['phone']);
            $pwd = trim($data['pwd']);
            $insertData['salt'] = parent::randomkeys(4);

            $userObj = new UserClass();
            $result = $userObj ->where('phone',$phone)->find();

            if (!$result){
                return parent::_responseResult('0','该手机号尚未注册');die();
            }

            $smsData = Db::table('tf_smscode') ->where('phone',$phone) ->where('code',$code) ->order('id desc')->field('code') ->limit(1) ->find();

            if (!$smsData){
                return parent::_responseResult('0','验证码不存在');die();
            }

            if ($code !== $smsData['code']){
                return parent::_responseResult('0','验证码输入有误');die();
            }

            $insertData['pwd'] = md5($pwd.'_'.$insertData['salt']);

            $res = $userObj ->where('phone',$phone) ->limit(1) ->update($insertData);
            if ($res){
                return parent::_responseResult('1','修改成功');
            }else{
                return parent::_responseResult('0','修改失败');
            }
        }

    }

    /***
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 用户注册
     */
    public function userReg(Request $request){

        if ($request ->isPost()){

            $data = input('input.');
            $phone = trim($data['phone']);
            $pwd = trim($data['pwd']);
            $code = trim($data['code']);
            $salt = parent::randomkeys(4);

            $userObj = new UserClass();
            $userData = $userObj ->where('phone',$phone) ->find();
            if ($userData){
                return parent::_responseResult('0','该手机用户已经注册过了');die();
            }

            $smsData = Db::table('tf_smscode') ->where('phone',$phone) ->where('code',$code) ->order('id desc')->field('code') ->limit(1) ->find();

            if (!$smsData){
                return parent::_responseResult('0','验证码不存在');die();
            }

            if ($code == $smsData['code']){
                //验证码正常
                $insert = array();
                $insert['phone'] = $phone;
                $insert['pwd'] = md5($pwd.'_'.$salt);
                $result = $userObj ->save($insert);
                if ($result){
                    return parent::_responseResult('1','注册成功');
                }else{
                    return parent::_responseResult('0','注册失败');
                }

            }else{

                return parent::_responseResult('0','验证码不正确');die();
            }


        }
    }

    /***
     * @param Request $request
     * @return array
     * 发送短信获取验证码
     */
    public function getsmsCode(Request $request){
        if ($request ->isPost()){
            $data = input('input.');
            $phone = trim($data['phone']);
            $code = rand(100000,999999);
            $type = isset($data['type'])?trim($data['type']) : 0;

            $sms_tpl_id = '1957930';
            $tpl_value = "#code#=$code&#hour#=20分钟";
            $smsData = parent::send_sms($sms_tpl_id, $tpl_value, $phone);

            if ($smsData['code'] == 0){ //发送成功

                $time = time();
                $date = date('Y-m-d');
                $tom = strtotime($date)+24*3600;
                $current = $tom - $time;
                $num = Cache::get('_sms_phone_'.$phone);
                if ($num){
                    if ($num >5){
                        return parent::_responseResult('0','当天只能获取5次验证码,请明天再试');die();
                    }else{
                        $num +=1;
                    }
                }else{
                    $num = 1;
                }

                Cache::set('_sms_phone_'.$phone,$num,$current);

                $insert['phone'] = $phone;
                $insert['code'] = $code;
                $insert['create_time'] = time();
                $insert['sms_type'] = $type;

                $res = Db::table('tf_smscode') ->insert($insert);
                if ($res){
                    return parent::_responseResult('1','发送短信成功');die();
                }else{
                    return parent::_responseResult('0','发送短信失败');die();
                }
            }else{
                return parent::_responseResult('0','发送短信失败');die();
            }

        }
    }


    /***
     * 用户退出登录
     */
    public function loginOut(){
        if (request() ->isPost()){
            $data = input('input.');
            $user_id = $data['user_id'];

            Session::delete('user_id_'.$user_id);
        }
    }


    public function weixin(){

    }

}