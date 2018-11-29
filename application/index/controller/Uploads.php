<?php
namespace app\index\controller;


use think\Image;
use app\admin\model\UserInfo;

class Uploads extends Common {
    public $file_move_path;   //上传文件移动服务器位置
    public $file_back_path;   //上传文件返回文件地址
    public $up_type;   //上传类型

    public $root_path;   //根目录路径
    public $root_url;   //根目录URL
    public $order;   //文件排序

    public function _initialize() {
        parent::_initialize();
//        if (!session('user_id')) {
//            exit();
//        }
        $this->up_type = 'image';   //上传文件类型
        $up = cache('DB_UP_CONFIG');
        if (!$up) {
            $up = db('config')->where('type', 'eq', 'up')->select();
            $up_config = [];
            foreach ($up as $k => $v) {
                $up_config[$v['k']] = $v['v'];
            }
            cache('DB_UP_CONFIG', $up_config);
            $up = cache('DB_UP_CONFIG');
        }
        $this->file_move_path = WEB_PATH . DS . $up['upload_path'] . DS . $this->up_type;
        $this->file_back_path = DS . $up['upload_path'] . DS . $this->up_type;

        $this->root_path = WEB_PATH . '/' . $up['upload_path'] . '/';
        $this->root_url = '/' . $up['upload_path'] . '/';
        $this->order = empty(input('get.order')) ? 'name' : strtolower(input('get.order'));
    }

    /**
     * kindeditor文件上传方法
     */
    public function upload() {
        $up_config = cache('DB_UP_CONFIG');   //获取数据库中的上传文件配置信息缓存
        $file = request()->file('imgs');
        if ($file) {
            $info = $file->validate(['size' => $up_config['image_size'], 'ext' => $up_config[$this->up_type . '_format']])
                ->move($this->file_move_path);
            if ($info) {
                if ($this->up_type == 'image' && $up_config['isprint'] == 1) {   //上传图片，加水印
                    $file = Image::open($this->file_move_path . DS . $info->getSaveName());   //打开上传的图片
                    //水印图片、水印位置、水印透明度 -> 保存同名图片覆盖
                    $file->water(WEB_PATH . $up_config['image_print'], $up_config['print_position'], $up_config['print_blur'])
                        ->save($this->file_move_path . DS . $info->getSaveName());
                }
                $file_path = $this->file_back_path . DS . $info->getSaveName();
                $file_path = $up_config['image_url'] . str_replace('\\', '/', $file_path);
                return ['error' => 0, 'url' => $file_path];
            } else {
                return ['error' => 1, 'message' => $file->getError()];
            }
        } else {
            return ['error' => 1, 'message' => '请选择文件'];
        }
    }

    /**
     * 上传图片
     */
    public function upload_apply() {
        if(!request()->isPost()){
            return;
        }
        $up_config = cache('DB_UP_CONFIG');   //获取数据库中的上传文件配置信息缓存
        $file = request()->file('imgs');
        if ($file) {
            $info = $file->validate(['size' => $up_config['image_size'], 'ext' => $up_config[$this->up_type . '_format']])
                ->move($this->file_move_path);
            if ($info) {
                if ($this->up_type == 'image' && $up_config['isprint'] == 1) {   //上传图片，加水印
                    $file = Image::open($this->file_move_path . DS . $info->getSaveName());   //打开上传的图片
                    //水印图片、水印位置、水印透明度 -> 保存同名图片覆盖
                    $file->water(WEB_PATH . $up_config['image_print'], $up_config['print_position'], $up_config['print_blur'])
                        ->save($this->file_move_path . DS . $info->getSaveName());
                }
                $file_path = $this->file_back_path . DS . $info->getSaveName();
                $file_path = $up_config['image_url'] . str_replace('\\', '/', $file_path);
                if(input('is_apply')){
                    $img_file_path = WEB_PATH.$file_path;
                    $image = \think\Image::open($img_file_path);
                    $image->thumb(130,200,\think\Image::THUMB_CENTER)->save($img_file_path);
                }
                return json(['code' => 1, 'url' => $file_path]);
            } else {
                return json(['code' => 0, 'message' => $file->getError()]);
            }
        } else {
            return json(['code' => 0, 'message' => '请选择文件']);
        }
    }

    public function _alert($msg)
    {
        return json_encode(['error' => 1, 'message' => $msg]);
    }

}