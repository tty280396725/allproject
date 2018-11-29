<?php
namespace app\admin\controller;

use http\Env\Request;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\Root;
use think\Cache;
use think\Controller;
use think\Lang;
use app\admin\model\AuthRule;
use expand\Auth;
use app\admin\model\Config;
use app\admin\controller\Login;
use app\admin\model\TokenUser;
use think\Request as Request2;
use think\Db;
use think\Loader;

/**
 * admin基础控制器
 * @author duqiu
 */
class Common extends Controller
{
    /**
     * 基础控制器初始化
     * @author duqiu
     */
    public function _initialize()
    {

        $this->restLogin();
        $userId = session('userId');
//        self::getAuthRule();die();

        self::DoLog($userId);//后台的操作日志

        define('UID', $userId);   //设置登陆用户ID常量

        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('ACTION_NAME', request()->action());

        $box_is_pjax = $this->request->isPjax();
        $this->assign('box_is_pjax', $box_is_pjax);

        $treeMenu = $this->treeMenu();
        $this->assign('treeMenu', $treeMenu);

        //加载多语言相应控制器对应字段
        if($_COOKIE['think_var']){
            $langField = $_COOKIE['think_var'];
        }else{
            $langField = config('default_lang');
        }
        Lang::load(APP_PATH . 'admin/lang/'.$langField.'/'.CONTROLLER_NAME.'.php');

        $auth = new Auth();
        if (!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME, UID)){
            $this->error(lang('auth_no_exist'), url('Login/index'));
        }
    }

    public function treeMenu()
    {
        $treeMenu = cache('DB_TREE_MENU_'.UID);
        if(!$treeMenu){
            $where = [
                'ismenu' => 1,
                'module' => 'admin',
            ];
            if (UID != '-1'){
                $where['status'] = 1;
            }
            $arModel = new AuthRule();
            $lists =  $arModel->where($where)->order('sorts ASC,id ASC')->select();
            $treeClass = new \expand\Tree();
            $treeMenu = $treeClass->create($lists);
            //判断导航tree用户使用权限
            foreach($treeMenu as $k=>$val){
                if( authcheck($val['name'], UID) == 'noauth' ){
                    unset($treeMenu[$k]);
                }
            }
            cache('DB_TREE_MENU_'.UID, $treeMenu);
        }
        return $treeMenu;
    }

    protected function getnewValue($value,$name){

        if (in_array($name,array('subject','class','sex','dstatus'))){
            if ($name == 'subject'){
                switch ($value){
                    case 1:
                        return '数学';
                        break;
                    case 2:
                        return '英语';
                        break;
                    case 3:
                        return '语文';
                        break;
                    case 4:
                        return '科技';
                        break;
                    case 5:
                        return '书画';
                        break;
                    default :
                        return $value;
                }
            }elseif ($name == 'class'){
                switch ($value){
                    case 1:
                        return '一年级';
                        break;
                    case 2:
                        return '二年级';
                        break;
                    case 3:
                        return '三年级';
                        break;
                    case 4:
                        return '四年级';
                        break;
                    case 5:
                        return '五年级';
                        break;
                    case 6:
                        return '六年级';
                        break;
                    case 7:
                        return '七年级';
                        break;
                    case 8:
                        return '八年级';
                        break;
                    case 9:
                        return '九年级';
                        break;
                    default :
                        return $value;
                }
            }elseif ($name == 'sex'){
                switch ($value){
                    case 0:
                        return '女';
                        break;
                    case 1:
                        return '男';
                        break;
                    default :
                        return $value;
                }
            }elseif ($name == 'dstatus'){
                switch ($value){
                    case 0:
                        return '待支付';
                        break;
                    case 1:
                        return '待审核';
                        break;
                    case -1:
                        return '审核失败';
                        break;
                    case 2:
                        return '待考试';
                        break;
                    default :
                        return $value;
                }
            }

        }

    }

    private function restLogin()
    {
        $login = new Login();
        $userId = session('userId');
        if (empty($userId)){   //未登录
            $login->loginOut();
        }
        $config = new Config();
        $login_time = $config->where(['type'=>'system', 'k'=>'login_time'])->value('v');
        $now_token = session('user_token');   //当前token
        $tkModel = new TokenUser();
        $db_token = $tkModel->where(['uid'=>$userId, 'type'=>'1'])->find();   //数据库token
        if ($db_token['token'] != $now_token){   //其他地方登录
            $this->loginBox(lang('login_other'));
        }else{
            /*if ($db_token['token_time'] < time()){   //登录超时
                $this->loginBox(lang('login_timeout'));
            }else{
                $token_time = time() + $login_time;
                $data = ['token_time' => $token_time];
                $tkModel->where(['uid'=>$userId, 'type'=>'1'])->update($data);
            }*/
        }
        return;
    }

    /***
     * 获取权限的内容
     */
    private function getAuthRule(){
        //$ruleData = Cache::get('get_all_auth_rule'); //获取权限数据
        if (empty($ruleData)){
            $data = Db::table('tf_auth_rule') ->field('name,title') ->select();
            $list = array();
            foreach ($data as $k=>$v){
                $keys[$k] = $v['name'];
                $list[$k] = $v['title'];
            }
            $ruleData = array();
            $ruleData['keys'] = $keys;
            $ruleData['list'] = $list;

            Cache::set('get_all_auth_rule',$ruleData,30*24*3600);
        }

        return $ruleData;


    }

    private function loginBox($info='')
    {
        if (request()->isGet()){
            $rest_login = 1;
            $this->assign('rest_login_info', $info);
            $this->assign('rest_login', $rest_login);
        }else{
            ajaxReturn($info, '', 2);
        }
    }

    /***
     * @param $userid
     * @note 后台用户操作日志
     * @author tian
     */
     protected function DoLog($userid){

        $request = Request2::instance();
        $data = $request ->param();
        unset($data['_pjax']);
        $insert = array();
        if (!empty($data)){
            $other =array('admin/log/index');
            $insert['route'] = $request ->path();
            $insert['ip'] = $request ->ip();
            $insert['uid'] = $userid;
            $insert['create_time'] = time();
            if ($request ->isGet()){
                $insert['getparams'] = json_encode($data);
            }

            if ($request ->isPost()){
                $insert['postparams'] = json_encode($data);
            }
            if (!in_array($insert['route'],$other)){
                $authData = self::getAuthRule();
                $authKey = $authData['keys'];
                $authList = $authData['list'];
                $target = explode('/',$insert['route']);
                $targetdat = $target[1].'/'.$target[2];
                $key = array_search($targetdat,$authKey);

                if ($key){
                    $insert['remark'] = $authList[$key];
                }
                Db::table('tf_caozuo_log') ->insert($insert);
            }

        }
    }


    /**
     * @param $url
     * @param int $timeout
     * @param array $options
     * @return mixed
     * @author:tian
     */

    protected function curl_get($url,$timeout=15,array $options = array())
    {
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_NOBODY=>false,
            //CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0',
            //CURLOPT_FOLLOWLOCATION=>1,
            CURLOPT_TIMEOUT => $timeout
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /***
     * @param $phone
     * @param $content
     * @return array
     * @author tian 待修改
     */
    protected function sendsms($phone,$content)
    {
        $msg='';
        $status=1;
        //短信通道
        $sms=array(
            'username' => 'dianzhi',
            'password' => 'tTdBl7W4i',
            'contents' => $content
        );
        $productid= 1512092;//95533;
        $content=$content;
        $url='http://www.ztsms.cn:8800/sendXSms.do?username='.$sms['username'].'&password='.$sms['password'].'&mobile='.$phone.'&content='. rawurlencode( $content ) .'&productid='.$productid;
        $res=self::curl_get($url);
        $res_arr=explode(',',$res);
        if(count($res_arr)==2&&((int)$res_arr[0]==1))//发送成功
        {
            return array('msg'=>$msg,'status'=>$status,'code'=>$code);
        }else
        {
            $res=strip_tags($res);
            //sys_debug('短信发送失败',"sendsms短信发送失败,返回结果:$res",LEVEL_NOTICE,0);
            $msg='短信发送失败,请稍后重试!';
            $status=0;
            return array('msg'=>$msg,'status'=>$status);
        }

    }

    /**
     * @param $length
     * @return string
     * 用户注册生成的密码盐
     */
    protected function randomkeys($length)
    {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};
        }
        return $key;
    }

    protected function send_sms($tpl_id, $tpl_value, $mobile){
        $url="http://yunpian.com/v1/sms/tpl_send.json";
        $encoded_tpl_value = urlencode("$tpl_value");
        $mobile = urlencode("$mobile");
        $post_string="apikey=44a6c37e54aa59edf7878436df2545c8&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
        return sock_post_2($url, $post_string);
    }

    protected function sock_post_2($url,$query){
        $data = "";
        $info=parse_url($url);
        //$fp=fsockopen($info["host"],80,$errno,$errstr,30);
        $fp=stream_socket_client($info["host"].':80',$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }

    /***
     * @param $headArr
     * @param $data
     * @param $fileName
     * 报表导出
     * 优点 导出表格贼快
     * 缺点 但是导出来的表格会有一些的兼容的问题
     */

    protected function importExcel2($headArr, $data, $fileName) {
        ob_end_clean();//清除缓冲区,避免乱码
        header("Content-type:application/octet-stream");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition:attachment;filename={$fileName}.xlsx");
        array_unshift($data,$headArr);
        foreach($data as $val){
            echo implode("\t", $val) . "\n";
        }

    }

    /***
     * @param $indexKey
     * @param $list
     * @param $filename
     * @param int $startRow
     * @param bool $excel2007
     * @return bool
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     * execl 报表导出的功能
     */

    function importExcel($indexKey,$list,$filename,$startRow=2,$excel2007=true)
    {
        require ROOT_PATH . '/extend/PHPExcel/PHPExcel.php';
        require ROOT_PATH .'/extend/PHPExcel/PHPExcel/Writer/Excel2007.php';

        if(empty($filename)) $filename = time();
        if(!is_array($indexKey)) return false;
        $header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        //初始化PHPExcel()
        $objPHPExcel = new \PHPExcel();
        //设置保存版本格式
        if($excel2007){
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $filename = $filename.'.xlsx';
        }else{
            $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
            $filename = $filename.'.xls';
        }
        //接下来就是写数据到表格里面去
        $objActSheet = $objPHPExcel->getActiveSheet();
//        $startRow = 2;
        $count = count($indexKey);
        foreach ($header_arr as $k=>$v){
            if ($k < $count){
                $objActSheet->setCellValue($header_arr[$k].'1',$indexKey[$k]);
            }
        }

        foreach ($list as $row) {
            foreach ($indexKey as $key => $value){
                //这里是设置单元格的内容
                $objActSheet->setCellValue($header_arr[$key].$startRow,$row[$value]);
            }		$startRow++;	}
        // 下载这个表格，在浏览器输出
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$filename.'');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    /***
     * @param array $headerArr
     * @param string $input
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * 数据表execl转数组
     * $header = array('id','name','phone'...) 一维数组转化为二位数组的键名
     * $input 上传input 的name值 默认为excel
     */

    protected function exportExcel($headerArr = array(),$input = 'execl')
    {

        require ROOT_PATH . '/extend/PHPExcel/PHPExcel.php';
        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel2007');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel5');

        //Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel2007');
        //获取表单上传文件

        $file = request()->file($input);
        if (!$file){
            return $this ->error('文件错误,上传失败');die();
        }

        $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'public/uploads/excel');  //上传验证后缀名,以及上传之后移动的地址

        if ($info) {
            //echo $info->getFilename();
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public/uploads/excel' . DS . $exclePath;//上传文件的地址
            $ext = $info->getExtension();

            if ($ext == 'xls'){
                $path = 'Excel5';
                $objReader = \PHPExcel_IOFactory::createReader($path);
            }else{
                $path = 'Excel2007';
                $objReader =new \PHPExcel_Reader_Excel2007($path);
            }

            $objReader->setReadDataOnly(true);
            $objReader->setLoadSheetsOnly(true);

            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            echo "<pre>";
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式
            array_shift($excel_array);  //删除第一个数组(标题);
            $arr = [];

            $count = count($headerArr);
            foreach ($excel_array as $k => $v) {
                for ($i = 0; $i < $count; $i++) {
                    $arr[$k][$headerArr[$i]] = $v[$i];
                }
            }
            return $arr;
        }

    }

    /***
     * @param $arr
     * @param $key
     * @return array
     * @$arr->传入数组   $key->判断的key值
     * @二维数组去重
     */
    protected function array_unset_tt($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                unset($value[$key]);  //有：销毁
            }else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }
}