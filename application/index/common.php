<?php
use app\index\model\Arctype;
use app\index\model\Archive;
use app\index\model\Config;
use app\index\model\Flink;
use app\index\model\Banner;


/**
 * @Title: ajaxReturn
 * @Description: todo(ajax提交返回状态信息)
 * @param string $info
 * @param url $url
 * @param string $status
 * @author duqiu
 * @date 2016-5-12
 */
function ajaxReturn($info='', $url='', $status='', $data = ''){
    if(!empty($url)){   //操作成功
        $result = array( 'info' => '操作成功', 'status' => 1, 'url' => $url, );
    }else{   //操作失败
        $result = array( 'info' => '操作失败', 'status' => 0, 'url' => '', );
    }
    if(!empty($info)){$result['info'] = $info;}
    if(!empty($status)){$result['status'] = $status;}
    if(!empty($data)){$result['data'] = $data;}
    echo json_encode($result);
    exit();
}


/**
 * @Title: channeldata
 * @Description: todo(当前ID的平级栏目)
 * @param int $pid 上级栏目ID
 * @return array
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function channeldata($pid){
    $arctype = new Arctype();
    return $arctype->channeldata($pid);
}

/**
 * @Title: channel
 * @Description: todo(直接输出导航链接)
 * @param int $pid 上级栏目ID
 * @param int $nowid 当前显示ID
 * @param string $ishome 是否显示首页
 * @param string $leftlabel 左标签
 * @param string $rightlabel 右标签
 * @param string $class 类名
 * @return string
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function channel($pid, $nowid='', $ishome='', $leftlabel="", $rightlabel="", $class=""){
    $arctype = new Arctype();
    return $arctype->channel($pid, $nowid, $ishome, $leftlabel, $rightlabel, $class);
}

/**
 * @Title: arctypefield
 * @Description: todo(指定查询栏目键值)
 * @param int $id 栏目ID
 * @param string $key 查询键值
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function arctypefield($id, $key){
    $arctype = new Arctype();
    return $arctype->arctypefield($id, $key);
}

/**
 * @Title: typename
 * @Description: todo(栏目名称)
 * @param int $id 栏目ID
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function typename($id){
    $arctype = new Arctype();
    return $arctype->typename($id);
}

/**
 * @Title: typelink
 * @Description: todo(栏目完整链接)
 * @param int $id 栏目ID
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function typelink($id){
    $arctype = new Arctype();
    return $arctype->typelink($id);
}

/**
 * @Title: toparctypedata
 * @Description: todo(返回当前栏目的顶级栏目数据)
 * @param int $id
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function toparctypedata($id){
    $arctype = new Arctype();
    return $arctype->topArctypeData($id);
}

/**
 * @Title: position
 * @Description: todo(当前位置)
 * @param int $id
 * @param string $home
 * @param string $line
 * @return string
 * @author 苏晓信
 * @date 2017年7月2日
 * @throws
 */
function position($id, $home="首页", $line=">"){
    $arctype = new Arctype();
    return $arctype->position($id, $home, $line);
}

/**
 * @Title: arclist
 * @Description: todo(查询栏目下的文章)
 * @param int $typeid 栏目ID（当前栏目下的所有[无限级]栏目ID）
 * @param int $limit 查询数量
 * @param string $flag 推荐[c] 特荐[a] 头条[h] 滚动[s] 图片[p] 跳转[j]
 * @param string $order 排序
 * @return array
 * @author 苏晓信
 * @date 2017年7月5日
 * @throws
 */
function arclist($typeid='0', $limit='', $flag='', $order='id DESC'){
    $archive = new Archive();
    return $archive->arclist($typeid, $limit, $flag, $order);
}

/**
 * @Title: prenext
 * @Description: todo(上一篇、下一篇)
 * @param array $archiveArr 当前文档数组
 * @return string
 * @author 苏晓信
 * @date 2017年7月5日
 * @throws
 */
function prenext($archiveArr){
    $archive = new Archive();
    return $archive->prenext($archiveArr);
}

/**
 * @Title: click
 * @Description: todo(文档点击数+1)
 * @param array $archiveArr 当前文档数组
 * @author 苏晓信
 * @date 2017年7月6日
 * @throws
 */
function click($archiveArr){
    $archive = new Archive();
    $archive->click($archiveArr);
}

/**
 * @Title: confv
 * @Description: todo(获取配置值)
 * @param string $k
 * @param string $type
 * @return string
 * @author 苏晓信
 * @date 2017年8月26日
 * @throws
 */
function confv($k, $type = 'web'){
    $config = new Config();
    return $config->confv($k, $type);
}

function flinks(){
    $flink = new Flink();
}

/**
 * @Title: banners
 * @Description: todo(banner模块数据)
 * @param int $mid
 * @param string $limit
 * @author 苏晓信
 * @date 2017年8月26日
 * @throws
 */
function banners($mid, $limit=''){
    $banner = new Banner();
    return $banner->banners($mid, $limit);
}

function tag(){
    die('哈哈 到这里了 ');
}

/***
 * @return mixed
 * 加载支付宝的支付的配置
 */
function alipay_confing(){
    $config['app_id']='20100919000241';
    $config['merchant_private_key']='MIIEowIBAAKCAQEAsBUqNm8NAKnukhMSFNBUlogaiZxwbM3usQQQrnpcCPhmuwqdekjkSS7eBnql5K21N641uR05G+7M1teqz1ng5Qo9aktJd1wLJ09dWnv7ijh+Bc6l+gin';
    $config['notify_url']='';
    $config['return_url']='';
    $config['charset']='UTF-8';
    $config['sign_type']='RSA2';
    $config['gatewayUrl']='https://openapi.alipaydev.com/gateway.do';
    $config['alipay_public_key']='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3QV6KrPDrmAPdFc1EqTsPO7kdlHhvs09noSEV4NUxO4XPyz5E62eJRxPlcBuSD1yzM84xBTNXo6WgTM6XuxvM74Bs6hXVm/WcZxSLIGAZ5N9tymx9+09fOMejOObydP/GOrEAV4KF5tB65+/aq4+rvp0w/QlhfmMHwjaazLXmdBjSzXj5x1v0Ep2uzPG4SgH+XCvM6SCN0mvSIKdZDPtIowPlpLXOqzMQIDAQAB';
    return $config;
}

//生成唯一订单号
function build_order_no()
{
    return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/** * 支付宝 电脑网站支付 *
 * @auhor hongweizhiyuan *
 * @param $out_trade_no
 * 商户订单号 *@param $subject
 * 订单名称 * @param $total_amount
 * 订单金额 * @param $body
 * 商品描述 * @example
 * alipayPagepay('201791711599526','商品标题','0.01','商品描述'); */
function alipayPagepay($out_trade_no,$subject,$total_amount,$body){
        //step1:获取配置
        import('alipay.pagepay.service.AlipayTradeService',EXTEND_PATH,'.php');
//        require_once  ROOT_PATH.'/extend/alipay/'
         // 加载交易服务类
         $config=config('alipay');

         $aop=new AlipayTradeService($config);
         //step2:加载表单，构造参数
         import('alipay.pagepay.buildermodel.AlipayTradePagePayContentBuilder',EXTEND_PATH,'.php');
         // 支付宝电脑网站支付
         $payRequestBuilder = new AlipayTradePagePayContentBuilder();
         $payRequestBuilder->setBody($body);
         $payRequestBuilder->setSubject($subject);
         $payRequestBuilder->setTotalAmount($total_amount);
         $payRequestBuilder->setOutTradeNo($out_trade_no);
          //step3:创建支付
         $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
         //输出表单
         var_dump($response);
      }









