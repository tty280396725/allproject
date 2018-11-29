<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:75:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\need\need.html";i:1540807781;s:77:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\base.html";i:1539158298;s:85:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_load_t.html";i:1539158298;s:82:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_top.html";i:1540522655;s:83:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_left.html";i:1540532309;s:84:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\list_action.html";i:1539158298;s:85:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_bottom.html";i:1540458843;s:85:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_load_b.html";i:1539158298;}*/ ?>
<?php if($box_is_pjax != 1): ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<?php endif; ?>
<title><?php echo \think\Lang::get('list'); ?></title>

<?php if($box_is_pjax != 1): ?>
<link rel="stylesheet" type="text/css" href="__STATIC__/global/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/global/bootstrap/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/system/iCheck/minimal/blue.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/system/select2/select2.min.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/system/dist/css/AdminLTE.min.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/system/dist/css/skins/skin-blue.min.css" />

<script type="text/javascript" src="__STATIC__/global/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="__STATIC__/global/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="__STATIC__/system/slimScroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="__STATIC__/system/dist/js/app.min.js"></script>
<script type="text/javascript" src="__STATIC__/global/jQuery/jquery.pjax.js"></script>

<link rel="stylesheet" type="text/css" href="__STATIC__/system/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="__STATIC__/system/kindeditor/kindeditor-all.js"></script>
<script type="text/javascript" src="__STATIC__/system/kindeditor/lang/zh-CN.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="__STATIC__/system/dist/js/html5shiv.min.js"></script>
<script type="text/javascript" src="__STATIC__/system/dist/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" type="text/css" href="__STATIC__/layui/css/layui.css" />
<script type="text/javascript" src="__STATIC__/layui/layui.js"></script>

<?php endif; if($box_is_pjax != 1): ?>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
<?php endif; if($box_is_pjax != 1): ?>
    <header class="main-header">
        <a href="#" class="logo"><span class="logo-mini"></span><span class="logo-lg">博奥竞赛</span></a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu">
                        <!--<a href="" target="_blank" ><?php echo \think\Lang::get('web_home'); ?></a>-->
                    </li>
                    <li class="dropdown messages-menu">
                        <a href="javascript:void(0);" class="delete-one" data-url="<?php echo url('Index/cleanCache'); ?>" data-id="-1" ><?php echo \think\Lang::get('clean_cache'); ?></a>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo cookie('avatar'); ?>" class="user-image">
                            <span class="hidden-xs"><?php echo cookie('name'); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="<?php echo cookie('avatar'); ?>" class="img-circle">
                                <p><?php echo cookie('name'); ?><small>Member since admin</small></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left"><a href="<?php echo url('User/edit', ['id' => cookie('uid')]); ?>" class="btn btn-default btn-flat">个人设置</a></div>
                                <div class="pull-right"><a href="<?php echo url('Login/loginOut'); ?>" class="btn btn-default btn-flat">退出</a></div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<?php endif; if($box_is_pjax != 1): ?>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image" style="height:47px;">
                    <img src="<?php echo cookie('avatar'); ?>" class="img-circle" alt="<?php echo cookie('name'); ?>">
                </div>
                <div class="pull-left info">
                    <p><?php echo cookie('name'); ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i>在线</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">菜单</li>
                <?php if(is_array($treeMenu) || $treeMenu instanceof \think\Collection || $treeMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $treeMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$oo): $mod = ($i % 2 );++$i;if($oo['level'] == '1' && $oo['name'] == 'Index/index'): ?>
                    <li><a href="<?php echo url(MODULE_NAME.'/'.$oo['name']); ?>"><i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span></a></li>
                    <?php elseif($oo['level'] == '1'): ?>
                    <li class="treeview">
                        <a href="javascript:void(0);">
                            <i class="<?php echo $oo['icon']; ?>"></i><span><?php echo $oo['title']; ?></span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if(is_array($treeMenu) || $treeMenu instanceof \think\Collection || $treeMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $treeMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$to): $mod = ($i % 2 );++$i;if($to['pid'] == $oo['id']): ?>
                            <li><a href="<?php echo url(MODULE_NAME.'/'.$to['name']); ?>"><i class="<?php echo $to['icon']; ?>"></i><?php echo $to['title']; ?></a></li>
                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </li>
                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </section>
    </aside>
<?php endif; ?>
    
    
    <div class="content-wrapper" id="pjax-container">
        
<section class="content-header">
    <h1>待考试列表</h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> 待考试列表</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!--操作提示 面板-->
            <div class="panel panel-info" style="background-color: #f5faff; border: 1px dashed #62b3ff;">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i>&nbsp;操作提示
                        </h6>
                    </div>
                </a>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body" style="color:rgb(103,103,103);">
                        1.本模板页面上展示的是各个赛事已经上传和填写了准考证和考试地点的用户列表!<br>
                        2. <span style="color: red;">导入的准考证表格模板,为报名列表已填写好的待考模板execl表格!</span> <br/>
                    </div>
                </div>
            </div>
            <!--操作提示 面板 end-->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="pull-left">
                        <?php echo authAction(CONTROLLER_NAME.'/create', 'create'); ?>
                        <?php echo authAction(CONTROLLER_NAME.'/delete', 'delete_all'); ?>
                        <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-cloud-upload">导入准考证表格</i>
                        </button>
                    </div>
                    <div class="box-tools" style="top:10px;" >
                        <form action="<?php echo search_url('search'); ?>" method="GET" pjax-search="" id="form_search">

                            <div class="input-group input-group-sm" style="width:250px; float: right;"  >
                                <input type="text" name="search" class="form-control pull-right" style="width: 200px;" value="<?php echo input('get.search'); ?>" placeholder="报名ID|用户ID" />

                                <div class="input-group-btn"><button type="submit" class="btn btn-default sreachs"><i class="fa fa-search"></i>搜索</button></div>
                                <!--<div class="input-group-btn">-->
                                    <!--<button type="button" class="btn btn-default sreachs" onclick="loadExecl()"><i class=""></i>导出表格</button>-->
                                <!--</div>-->
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-danger " onclick="loadTemplate()"><i class=""></i>下载准考证模板</button>
                                </div>

                            </div>

                            <div class="input-group input-group-sm" style=" float: right; width: 160px;">
                                <select name="cid" class="form-control" id="competition">
                                    <option value="">选择竞赛项目</option>
                                    <?php foreach($compData as $v): ?>
                                    <option value="<?php echo $v['id']; ?>" <?php if($cid == $v['id']): ?> selected <?php endif; ?>><?php echo $v['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="input-group input-group-sm" style=" float: right; width: 130px;">
                                <select name="class" class="form-control">
                                    <option value="">年级选择</option>
                                    <?php foreach($classData as $v): ?>
                                    <option value="<?php echo $v['name']; ?>" <?php if($class == $v['name']): ?>  selected <?php endif; ?>><?php echo $v['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-hover table-sort">
                        <tr>
                            <th width="35"><input type="checkbox" class="minimal checkbox-toggle"></th>
                            <th><?php echo \think\Lang::get('id'); ?></th>
                            <th>姓名</th>
                            <th>用户ID</th>
                            <th>照片</th>
                            <th>竞赛<?php echo table_sort('cid'); ?></th>
                            <th>年级<?php echo table_sort('class'); ?></th>
                            <th>性别</th>
                            <th>考试时间</th>
                            <th>考场</th>
                            <th>准考证号</th>
                            <th>创建时间</th>
                            <th width="204"><?php echo \think\Lang::get('action'); ?></th>
                        </tr>
                        <?php if(is_array($dataList) || $dataList instanceof \think\Collection || $dataList instanceof \think\Paginator): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td style="vertical-align:middle"><input type="checkbox" name="id[]" value="<?php echo $vo['id']; ?>" class="minimal checkbox"></td>
                            <td style="vertical-align:middle"><?php echo $vo['id']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['name']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['uid']; ?></td>
                            <td style="vertical-align:middle"><img src="<?php echo $vo['pic']; ?>" style="width:130px;height: 200px;" /></td>
                            <td style="vertical-align:middle">
                                <?php echo $vo['title']; ?>
                            </td>
                            <td style="vertical-align:middle"><?php echo $vo['class']; ?></td>
                            <td style="vertical-align:middle"><?php if($vo['sex'] == 0): ?> 女 <?php elseif($vo['sex'] == 1): ?> 男 <?php endif; ?></td>
                            <td style="vertical-align:middle"><?php echo date("Y-m-d",$vo['date']); ?>---<?php echo $vo['time']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['addr']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['tnum']; ?></td>
                            <td style="vertical-align:middle"><?php echo date('Y-m-d H:i',$vo['create_time']); ?></td>

                            <td style="vertical-align:middle">
                                <?php echo authAction(CONTROLLER_NAME.'/edit', 'edit', ['id' => $vo['id']]); ?> 
<?php echo authAction(CONTROLLER_NAME.'/delete', 'delete', $vo['id']); ?> 
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <?php echo $dataList->render(); ?>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">导入成绩表(excel)</h4>
                </div>
                <div class="modal-body">
                    <form action="/admin/need/importExecl" method="post" id="form" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="execl" value="">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="importExcel()">上传</button>
                </div>
            </div>
        </div>
    </div>



</section>
<script type="text/javascript">

    function importExcel(){
        $('#form').submit();
    }


    function putTicket(id=0,status=0){
        var id = id;
        var status = status;

        if (status == 1){
            //群个审核
            var arr = [];
            $('.checkbox').each(function () {
                if ($(this).prop("checked")) {
                    var id = $(this).val();
                    arr.push(id);
                }
            });

            if (arr.length > 0) {
                var idStr = arr.join(',');
            }else{
                alert('请选择选项');
                return false;
            }
        } else{
            //单个审核
            var idStr = id;

        }

        $.post('/admin/apply/makeCard',{id:idStr,status:status},function (data) {
            $.amaran({'message':data.info});
            if (data.status == 1){
                window.location.reload();
            }
        },'json')
    }


    //报表导出的功能
    function loadExecl(){
        $('#form_search').attr('action','/admin/need/download')
        $('#form_search').submit();
        $('#form_search').attr("action","");
    }

    //导出模板

    // function loadTemplate(){
    //     window.location.href='/admin/apply/download';
    // }
    //导出模板

    function loadTemplate(){
        var cid = $('#competition').val();
         if (cid == '' || cid == 0 || cid == undefined){
             alert('前选择竞赛的项目');return;
         }
        $('#form_search').attr("action","/admin/need/download");
        $('#form_search').submit();
        $('#form_search').attr("action","");
    }


    $(function(){
        /*ajax页面加载icheck，有该控件的页面才需要*/
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        /*全选-反选*/
        $('.checkbox-toggle').on('ifChecked', function(event){
            var _this = $(this);
            var _table = _this.closest('.table');
            _table.find("tr td input[type='checkbox']").iCheck("check");
        });
        $('.checkbox-toggle').on('ifUnchecked', function(event){
            var _this = $(this);
            var _table = _this.closest('.table');
            _table.find("tr td input[type='checkbox']").iCheck("uncheck");
        });

        $('.editable').editable({
            emptytext: "empty",
            params: function(params) {      //参数
                var data = {};
                data['id'] = params.pk;
                data[params.name] = params.value;
                return data;
            },
            success: function(response, newValue) {
                var res = $.parseJSON( response );
                if(res.status == 1){
                }else{
                    return res.info;
                }
            }
        });

        <?php if($rest_login == 1): ?>
        restlogin('<?php echo $rest_login_info; ?>');   //登录超时
        <?php endif; ?>
        })
</script>

    </div>
    
        
<?php if($box_is_pjax != 1): ?>
    <footer class="main-footer">
        <div class="pull-right"></div>

    </footer>

<?php endif; if($box_is_pjax != 1): ?>
</div>
<?php endif; if($box_is_pjax != 1): ?>
<script type="text/javascript" src="__STATIC__/global/jQuery/jquery.form.js"></script>

<script type="text/javascript" src="__STATIC__/system/editable/bootstrap-editable.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/system/editable/bootstrap-editable.css" />

<script type="text/javascript" src="__STATIC__/global/nprogress/nprogress.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/global/nprogress/nprogress.css" />

<link rel="stylesheet" type="text/css" href="__STATIC__/global/jQuery-gDialog/animate.min.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/global/Amaranjs/amaran.min.css" />
<script type="text/javascript" src="__STATIC__/global/Amaranjs/jquery.amaran.min.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/global/bootstrap/js/bootstrap-dialog.min.css" />
<script type="text/javascript" src="__STATIC__/global/bootstrap/js/bootstrap-dialog.min.js"></script>

<script type="text/javascript" src="__STATIC__/system/datetimepicker/moment-with-locales.min.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/system/datetimepicker/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="__STATIC__/system/datetimepicker/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="__STATIC__/system/multiselect/multiselect.min.js"></script>

<script type="text/javascript" src="__STATIC__/system/iCheck/icheck.min.js"></script>

<script type="text/javascript" src="__STATIC__/system/select2/select2.min.js"></script>
<script type="text/javascript" src="__STATIC__/system/select2/i18n/zh-CN.js"></script>

<link rel="stylesheet" type="text/css" href="__STATIC__/system/bootstrap-switch/bootstrap-switch.min.css" />
<script type="text/javascript" src="__STATIC__/system/bootstrap-switch/bootstrap-switch.min.js"></script>

<link rel="stylesheet" type="text/css" href="__STATIC__/global/cropper/cropper.min.css" />
<script type="text/javascript" src="__STATIC__/global/cropper/cropper.min.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/global/cropper/cropper.main.css" />
<script type="text/javascript" src="__STATIC__/global/cropper/cropper.main.js"></script>

<script type="text/javascript" src="__STATIC__/system/chart/Chart.min.js"></script>

<script type="text/javascript" src="__STATIC__/system/dist/js/common.js"></script>
<?php endif; if($box_is_pjax != 1): ?>
</body>
</html>
<?php endif; ?>