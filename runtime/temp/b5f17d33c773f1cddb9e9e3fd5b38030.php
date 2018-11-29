<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:77:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\apply\index.html";i:1540807995;s:77:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\base.html";i:1539158298;s:85:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_load_t.html";i:1539158298;s:82:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_top.html";i:1540522655;s:83:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_left.html";i:1540532309;s:84:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\list_action.html";i:1539158298;s:85:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_bottom.html";i:1540458843;s:85:"F:\PHPTutorial\WWW\jing_sai\public/../application/admin\view\public\admin_load_b.html";i:1539158298;}*/ ?>
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
    <h1>报名列表</h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> 报名列表</li>
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
                        1.本模块展示用户各个赛事的用户报名列表!<br/>
                        2.在本模块中您可以给对每个报名的信息进行查询和审核以及分类<span style="color: red;font-size: 10px;">(选择分类后点击搜索再进行导出)</span>导出Excel的功能!<br/>
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
                        <!--<a class="btn btn-sm btn-primary" href="javascript:putTicket(0,1);" ><i class="glyphicon glyphicon-ok"></i>批量准考证</a>-->
                        <a class="btn btn-sm btn-success" href="javascript:pass(1);" > <i class="glyphicon glyphicon-check"></i>批量通过</a>
                        <a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal2" data-whatever="-1" href="javascript:void(0);" > <i class="glyphicon glyphicon-remove-sign"></i>批量拒绝</a>

                    </div>
                    <div class="box-tools" style="top:10px;" >
                        <form action="<?php echo search_url('search'); ?>" method="GET" pjax-search="" id="form_search">

                            <div class="input-group input-group-sm" style="width:250px; float: right;"  >
                                <input type="text" name="search" class="form-control pull-right" style="width: 200px;" value="<?php echo input('get.search'); ?>" placeholder="请输入报名ID|用户ID" />

                                <div class="input-group-btn"><button type="submit" class="btn btn-default sreachs"><i class="fa fa-search"></i>搜索</button></div>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default sreachs" onclick="loadExecl()"><i class=""></i>导出表格</button>
                                </div>
                            </div>

                            <div class="input-group input-group-sm" style=" float: right; width: 130px;">
                                <select name="cid" class="form-control">
                                    <option value="">选择竞赛</option>
                                    <?php foreach($compData as $v): ?>
                                    <option value="<?php echo $v['id']; ?>" <?php if($cid == $v['id']): ?> selected <?php endif; ?>><?php echo $v['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="input-group input-group-sm" style=" float: right; width: 130px;">
                                <select name="oid" class="form-control">
                                    <option value="">选择机构</option>
                                    <?php foreach($orgData as $v): ?>
                                    <option value="<?php echo $v['id']; ?>" <?php if($oid == $v['id']): ?> selected <?php endif; ?>><?php echo $v['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group input-group-sm" style=" float: right; width: 120px;">
                                <select name="dstatus" class="form-control">
                                    <option value="">选择状态</option>
                                    <option value="1" <?php if($dstatus == '1'): ?> selected <?php endif; ?>>待审核</option>
                                    <option value="-1" <?php if($dstatus == '-1'): ?> selected <?php endif; ?>>审核失败</option>
                                    <option value="2" <?php if($dstatus == '2'): ?> selected <?php endif; ?>>待考试</option>
                                </select>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-hover table-sort">
                        <tr>
                            <th width="35"><input type="checkbox" class="minimal checkbox-toggle"></th>
                            <th><?php echo \think\Lang::get('id'); ?><?php echo table_sort('id'); ?></th>
                            <th>姓名</th>
                            <th>照片</th>
                            <th>竞赛</th>
                            <th>学科</th>
                            <th>年级<?php echo table_sort('class'); ?></th>
                            <th>性别</th>
                            <th>地区</th>
                            <th>费用</th>
                            <th>状态</th>
                            <th>准考证</th>
                            <th>培训机构</th>
                            <th>家长姓名(电话)</th>
                            <th>创建时间</th>
                            <th width="204"><?php echo \think\Lang::get('action'); ?></th>
                        </tr>
                        <?php if(is_array($dataList) || $dataList instanceof \think\Collection || $dataList instanceof \think\Paginator): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td style="vertical-align:middle"><input type="checkbox" name="id[]" value="<?php echo $vo['state_id']; ?>" class="minimal checkbox"></td>
                            <td style="vertical-align:middle"><?php echo $vo['id']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['name']; ?></td>
                            <td style="vertical-align:middle"><a href="<?php echo $vo['pic']; ?>"><img src="<?php echo $vo['pic']; ?>" style="width:130px;height: 200px;" /></a></td>
                            <td style="vertical-align:middle">
                                <?php echo $vo['title']; ?>
                            </td>
                            <td style="vertical-align:middle"><?php if($vo['subject'] == 1): ?> 数学 <?php elseif($vo['subject'] == 2): ?> 语文 <?php elseif($vo['subject'] == 3): ?> 英语 <?php elseif($vo['subject'] == 4): ?> 科技 <?php elseif($vo['subject'] == 5): ?> 书画 <?php endif; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['class']; ?></td>
                            <td style="vertical-align:middle"><?php if($vo['sex'] == 0): ?> 女 <?php else: ?> 男 <?php endif; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['area']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['cost']; ?></td>
                            <td style="vertical-align:middle"><?php if($vo['dstatus'] == 0): ?><span style="color: red;">待支付</span><?php elseif($vo['dstatus'] == 1): ?> <span style="color: orange;">待审核</span><?php elseif($vo['dstatus'] == -1): ?> <span style="color: red;">审核失败(<?php echo $vo['content']; ?>)</span><?php elseif($vo['dstatus'] == 2): ?> <span style="color: greenyellow;">待考试</span>  <?php endif; ?></td>
                            <td style="vertical-align:middle"><?php if($vo['subject'] != 5): if($vo['is_create'] == 0): ?> <span style="color: red;">尚未上传</span><?php elseif($vo['is_create'] == 1): ?> <span style="color: green;">已经上传</span> <?php endif; ?> </td><?php else: ?>无 <?php endif; ?>
                            <td style="vertical-align:middle"><?php echo $vo['oname']; ?></td>
                            <td style="vertical-align:middle"><?php echo $vo['pname']; ?>(<?php echo $vo['phone']; ?>)</td>
                            <td style="vertical-align:middle"><?php echo date('Y-m-d H:i',$vo['create_time']); ?></td>
                            <td style="vertical-align:middle">
                                <!--<a class="btn btn-primary btn-xs" href="javascript:putTicket('<?php echo $vo['id']; ?>');" ><i class="glyphicon glyphicon-ok"></i>生成准考证</a>-->
                                <?php if($vo['dstatus'] == 1): ?>
                                <a href="javascript:pass(0,'<?php echo $vo['state_id']; ?>');" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-check"></i>通过</a>
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal2" data-whatever="<?php echo $vo['state_id']; ?>" class="btn btn-warning btn-xs"> <i class="glyphicon glyphicon-remove-sign"></i>拒绝</a>
                                <?php endif; ?>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">编辑考场</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group" style="padding: 10px;">
                            <input type="hidden" name="cid" id="model-cid" value="" >
                            <label for="recipient-name" class="control-label">考场地址:</label>
                            <textarea name="address" class="form-control" rows="4" placeholder="请输入考场地址" id="recipient-name"></textarea>
                            <!--<input type="text" name="address" class="form-control" id="recipient-name">-->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="editAddress()">修改</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel2">填写原因</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group" style="padding: 10px;">
                            <input type="hidden" name="aid" id="model-aid" value="" >
                            <label for="recipient-name" class="control-label">填写原因:</label>
                            <textarea name="content" class="form-control" rows="4" placeholder="填写拒绝原因"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="refue()">提交</button>
                </div>
            </div>
        </div>
    </div>



</section>
<script type="text/javascript">


    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) //
        var recipient = button.data('whatever') //
        var modal = $(this)
        modal.find('#model-cid').val(recipient)
    });

    function editAddress(){
        var  address = $("textarea[name=address]").val();
        var  cid = $("input[name=cid]").val();
        if (address !== ''){
            $.post('/admin/apply/editAddress',{address:address,id:cid},function (res) {

                // if (res.status == 1){
                    window.location.reload();
                // }
            })
        }
    }

    $('#exampleModal2').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) //
        var recipient = button.data('whatever') //
        var modal = $(this)
        modal.find('#model-aid').val(recipient)
    });


    function refue() {

        var aid = $("input[name=aid]").val();
        var content = $("textarea[name=content]").val();
        if (content == ''){
            alert('请填写原因');
            return false;
        }

        if (aid == -1){
            var arr = [];
            $('.checkbox').each(function(){
                if( $(this).prop("checked") ){
                    var id = $(this).val();
                    arr.push(id);
                }
            });

            if(arr.length > 0){
                var idStr = arr.join(',');
            }else{
                alert('请选择选项');
                return false;
            }

        }else{
            var idStr = aid;
        }

        $.post('/admin/apply/refue',{id:idStr,content:content},function (data) {
            $.amaran({'message':data.info});
            if (data.status == 1){
                window.location.reload();
            }
        },'json');

    }
    
    function pass(status,id=0) {

        if (confirm('是否确认操作')) {
            var status = status;
            var id = id;

            if (id == 0){
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
            $.post('/admin/apply/check', {id:idStr,status:status}, function (data) {
                $.amaran({'message':data.info});
                if (data.status == 1){
                    window.location.reload();
                }
            },'json')
        }
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
        $('#form_search').attr("action","/admin/apply/execl");
        $('#form_search').submit();
        // window.location.href='/admin/apply/execl';
    }


    $(function(){
        /*ajax页面加载icheck，有该控件的页面才需要*/
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        /*ajax页面加载icheck，有该控件的页面才需要*/
        $(".select2").select2({language:"zh-CN"});

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