<?php
namespace app\index\controller;

use app\index\model\Competition;

class Index extends Common
{
    protected $competition; //竞赛表

    public function _initialize(){
        parent::_initialize();
        $this->competition = new Competition();
    }

    public function index() {
        die('访问错误');
    }

    /**
     * 获取竞赛列表
     * @return \think\response\Json
     */
    public function comp_list() {
        $list = $this->competition->get_list();
        $list ? $res = parent::_responseResult('1', 'ok', $list) : $res = parent::_responseResult('0', '获取信息失败');
        return $res;
    }

    /***
     *搜索赛事列表
     * // 竞赛状态  1 报名中 2 比赛中 3 已结束
     */
    public function search_competition(){
        if (request() ->isGet()){
            $data = input('get.');
            $where = [];
            !isset($data['p']) && $data['p'] = 1;
            if(isset($data['search'])) {
                $search = trim($data['search']);
                $search && $where['title'] = ['LIKE', '%' . $search . '%'];
            }
            $field = ['id', 'title', 'com_des', 'class', 'startTime', 'endTime','date'];
            $list = model('Competition')->where($where)->field($field)->order('startTime asc')->page($data['p'], 5)->select();
            $date = date('Y-m-d',time());
            foreach ($list as $k => $v){
                $list[$k]['date'] = date("Y-m-d",$v['date']);
                if ($date < $v['endTime'] && $date >= $v['startTime']){
                    $v['status'] = 1;
                }elseif($date == date("Y-m-d",$v['date'])){
                    $v['status'] = 2;
                }else{
                    $v['status'] = 3;
                }
            }
            $count = model('Competition')->where($where)->field('id') ->count();

            return  parent::_responseResult('1', '返回成功', array('list'=>$list,'totalPage'=>ceil($count/5)));
        }
    }

    /**
     * 竞赛详情
     * @return \think\response\Json
     */
    public function comp_info(){
        $info = $this->competition->get_info();
        $info ? $res = parent::_responseResult('1', 'ok', $info) : $res = parent::_responseResult('0', '获取信息失败');
        return $res;
    }

    /**
     * 报名表单需要的信息或者报名审核未通过时获取报名信息
     * @return \think\response\Json
     */
    public function com_form(){
        $info = $this->competition->get_form($this->user_id);
        $info ? $res = parent::_responseResult('1', 'ok', $info) : $res = parent::_responseResult('0', '获取信息失败');
        return $res;
    }

}
