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
            $data = input('param.');
            $phone = $data['phone'];
            $userObj = new UserClass();

            $userData = $userObj ->where('phone',$phone) ->field('id,pwd,salt,status,phone') ->find();

            if (!$userData){
                return parent::_responseResult('0','该手机用户不存在');die();
            }

            $pwd = $userData['pwd'];
            $repwd = md5($data['pwd'].'_'.$userData['salt']);
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
                return parent::_responseResult('1','登录成功',array('phone'=>$userData['phone']));
            }else{
                return parent::_responseResult('0','登录失败');
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
            $data = input('param.');
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
                return parent::_responseResult('1','登录成功',array('phone'=>$phone));die();
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
            $data = input('param.');
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
               // return parent::_responseResult('0','验证码不存在');die();
            }

            if ($code !== $smsData['code']){
                //return parent::_responseResult('0','验证码输入有误');die();
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
            $data = input('param.');
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
                //return parent::_responseResult('0','验证码不存在');die();
            }

            //if ($code == $smsData['code']){
                //验证码正常
                $insert = array();
                $insert['phone'] = $phone;
                $insert['pwd'] = md5($pwd.'_'.$salt);
                $insert['salt'] = $salt;
                $result = $userObj ->save($insert);
                if ($result){
                    return parent::_responseResult('1','注册成功');
                }else{
                    return parent::_responseResult('0','注册失败');
                }

//            }else{
//
//                return parent::_responseResult('0','验证码不正确');die();
//            }


        }
    }


    /***
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 修改手机号码
     */
    public function reloadPhone(Request $request){

        if ($request ->isPost()){
            $data = input('param.');
            $phone = trim($data['phone']);
            $pwd = trim($data['pwd']);
            $code = trim($data['code']);

            if (empty($phone) || empty($pwd) || empty($code)){
                return parent::_responseResult('0','参数错误');die();
            }

            $userObj = new UserClass();
            $userData = $userObj ->where('phone',$phone)->field('id') ->find();
            if ($userData){
                return parent::_responseResult('0','该手机用户已经存在');die();
            }

            $smsData = Db::table('tf_smscode') ->where('phone',$phone) ->where('code',$code) ->order('id desc')->field('code') ->limit(1) ->find();

            if (!$smsData){
                //return parent::_responseResult('0','验证码不存在');die();
            }

            //if ($code == $smsData['code']){
            //验证码正常
                $user_id = $this ->user_id;
                $saltData = $userObj ->where('id',$user_id) ->field('salt') ->find();

                if (!$saltData){
                    return parent::_responseResult('0','用户信息不存在');die();
                }
                $salt = $saltData['salt'];
                $pwd = md5($pwd.'_'.$salt);

                $checkData = $userObj ->where('id',$user_id) ->where('pwd',$pwd) ->field('id') ->find();
                if (!$checkData){
                    return parent::_responseResult('0','密码错误');die();
                }

                $update = array();
                $update['phone'] = $phone;
                $result = $userObj ->where('id',$user_id) ->update($update);

                if ($result){
                     return parent::_responseResult('1','修改成功');
                }else{
                    return parent::_responseResult('0','修改失败');
                }

//            }else{
//
//                return parent::_responseResult('0','验证码不正确');die();
//            }


        }
    }


    public function get_msg(){

        require_once 'SmsDemo.php';

        $msgObj = new SmsDemo();

        var_dump($msgObj::sendSms());die();

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
            $smsData = parent::sendsms($sms_tpl_id, $tpl_value, $phone);

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
			session(null);
			return parent::_responseResult('1','退出成功');
        }
    }

    /***
     * @return bool|mixed|string
     * 唤起微信的扫码的页面
     */
    public function weixin_evoke(){
        if (request() ->isGet()){
            $redirect_uri="http://你的微信开放平台绑定域名下处理扫码事件的方法";  //替换到处理微信扫码的处理weixin_handle
            $redirect_uri=urlencode($redirect_uri);//该回调需要url编码
            $appID="你的appid";
            $scope="snsapi_login";//写死，微信暂时只支持这个值

            //准备向微信发请求
            $url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $appID."&redirect_uri=".$redirect_uri
                ."&response_type=code&scope=".$scope."&state=STATE#wechat_redirect";
            //请求返回的结果(实际上是个html的字符串)
            $result = file_get_contents($url);
            //替换图片的src才能显示二维码
            $result = str_replace("/connect/qrcode/", "https://open.weixin.qq.com/connect/qrcode/", $result);
            return $result; //返回页面
        }
    }

    /***
     *  微信回调的处理方法
     */
    public function weixin_handle(){
        if (request()->isGet()){
            $code = $_GET["code"];
            $appid = "你的appid";
            $secret = "你的secret";
            if (!empty($code))  //有code
            {
                //通过code获得 access_token + openid
                $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid
                    . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
                $jsonResult = file_get_contents($url);
                $resultArray = json_decode($jsonResult, true);
                $access_token = $resultArray["access_token"];
                $openid = $resultArray["openid"];

                //通过access_token + openid 获得用户所有信息,结果全部存储在$infoArray里,后面再写自己的代码逻辑
                $infoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid;
                $infoResult = file_get_contents($infoUrl);
                $infoArray = json_decode($infoResult, true);

                if ($infoArray){
                    //拿到手机号码 -> 用户号码查数据库(存在)  -> 进行同步(分支修改)
                    //                           (不存在) -> 携带参数 跳转去注册
                    //           -> 数据库电话值不存在 -> 携带参数 跳转去注册  |页面逻辑在下面

                }

            }

        }
    }


    /***
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 微信登陆的值
     */
    public function weixin_login(Request $request){
        if (request() ->isGet()){
            $data = input('get.');
            $unionid = $data['unionid'];
            $openid = $data['openid'];

            $userObj = new UserClass();

            if (empty($unionid) || empty($openid)){
                return parent::_responseResult('0','参数错误');die();
            }

            $userData = $userObj ->where('unionid',$unionid)->where('openid',$openid) ->field('id') ->find();
            if (!$userData){
                //首次绑定 微信
                return parent::_responseResult('2','请求成功跳转到注册页面');die();  //????
            }else{
                //微信登录成功
                $id = $userData['id'];
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
    }

}
