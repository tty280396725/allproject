<?php
namespace app\admin\controller;

use think\Loader;
use think\Controller;
use excel\PHPExcel;

class Excel extends Controller{

    public function excel()
     {
         require ROOT_PATH.'/extend/PHPExcel/PHPExcel.php';

         $path = dirname(__FILE__); //找到当前脚本所在路径
         Loader::import('PHPExcel.Classes.PHPExcel');//手动引入PHPExcel.php
         Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
         $PHPExcel = new \PHPExcel();//实例化

         $PHPSheet = $PHPExcel->getActiveSheet();
         $PHPSheet->setTitle("demo"); //给当前活动sheet设置名称
         $PHPSheet->setCellValue("A1","姓名")->setCellValue("B1","分数");//表格数据
         $PHPSheet->setCellValue("A2","张三")->setCellValue("B2","2121");//表格数据
         $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007");//创建生成的格式
         header('Content-Disposition: attachment;filename="表单数据.xlsx"');//下载下来的表格名
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件

     }

     public function insertExecl(){

         if(request() -> isPost()) {
             Loader::import('PHPExcel.Classes.PHPExcel');
             Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
             Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel5');
             //获取表单上传文件
             $file = request()->file('excel');
             $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'public');  //上传验证后缀名,以及上传之后移动的地址  E:\wamp\www\bick\public
             if($info) {
                 //              echo $info->getFilename();
                 $exclePath = $info->getSaveName();  //获取文件名
                 $file_name = ROOT_PATH . 'public' . DS . $exclePath;//上传文件的地址
                 $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
                 $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                 echo "<pre>";
                 $excel_array=$obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式
                 array_shift($excel_array);  //删除第一个数组(标题);
                 $city = [];
                 foreach($excel_array as $k=>$v) {
                     $city[$k]['name'] = $v[0];
                     $city[$k]['number'] = $v[1];
                 }
                 dump($city);

             }else {
                 echo $file->getError();
             }

         }else {
             die('ss');
             return $this -> fetch();
         }
     }


     function test(){
         $user = new Users();
         require ROOT_PATH.'/extend/PHPExcel/PHPExcel.php';
         $excel = new \PHPExcel();
         $letter = array('A','B','C','D', 'E', 'F');
         $tableheader = array('ID','名称','手机','评分','评分增量','总评分');
         for($i = 0;$i < count($tableheader);$i++) {
             $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setBold(true);
             $excel->getActiveSheet()->getColumnDimension($letter[$i])->setWidth(20);
             $excel->getActiveSheet()->getStyle("$letter[$i]1")->getAlignment()->setHorizontal('center');
             $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setSize(14);
             $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
         }
         $data = $user->gradeNumExcel();
         for ($i = 2;$i <= count($data) + 1;$i++) {
             $j = 0;
             foreach ($data[$i - 2]->toArray() as $value) {
                 $excel->getActiveSheet()->getStyle("$letter[$j]$i")->getAlignment()->setHorizontal('center');
                 $excel->getActiveSheet()->getStyle("$letter[$j]$i")->getFont()->setSize(14);
                 $excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
                 $j++;
             }
         }
         $write = new \PHPExcel_Writer_Excel5($excel);
         header("Pragma: public");
         header("Expires: 0");
         header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
         header("Content-Type:application/force-download");
         header("Content-Type:application/vnd.ms-execl");
         header("Content-Type:application/octet-stream");
         header("Content-Type:application/download");
         header('Content-Disposition:attachment;filename="coach_fraction.xls"');
         header("Content-Transfer-Encoding:binary");
         $write->save('php://output');
     }



     function tsext(){

     }



}
