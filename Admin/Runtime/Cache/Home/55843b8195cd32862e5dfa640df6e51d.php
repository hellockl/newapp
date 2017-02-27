<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>房金所业务操作系统</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap 3.3.4 -->
    <link href="/newapp/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- FontAwesome 4.3.0 -->
    <link href="/newapp/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons 2.0.0 --
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="/newapp/assets/vendor/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
    <link href="/newapp/assets/vendor/jquery-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
    <!-- iCheck -->
    <link href="/newapp/assets/vendor/iCheck/flat/blue.css" rel="stylesheet" type="text/css"/>
    <link href="/newapp/assets/css/admin/jquery.dialog.css" rel="stylesheet" type="text/css"/>
    <link href="/newapp/assets/css/admin/style.css?ver=20161124" rel="stylesheet" type="text/css"/>
    <link href="/newapp/assets/css/admin/skin-red.css" rel="stylesheet" type="text/css"/>
    <link href="/newapp/assets/css/litebox.css" rel="stylesheet" type="text/css">
</head>
<body class="skin-red">
<div class="wrapper sidebar-collapsed">

    <!--IE 9 以下版本抛出警告框；-->
    <!--[if lt IE 9]>
    <div class="news-ticker bg-orange">
        <div class="container">
            <ul id="news-ticker-content" class="list-unstyled mbn">
                <li>提示：您目前的浏览器不支持该应用，请升级到IE9及以上版本，建议使用<a href="#">Chrome <span
                        class="fa fa-download"></span></a> , <a href="#">FireFox</a>等浏览器。
                </li>
            </ul>
            <a id="news-ticker-close" href="javascript:;"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <![endif]-->
    <!--IE 9 以下版本抛出警告框结束；-->
    <!--BEGIN LEFT MENU-->
    <aside class="main-sidebar" style="overflow-y:auto;">
        <section class="sidebar">
            <div class="logo">
                <span><b>FJS CRM</b></span>
            </div>
                <ul class="sidebar-menu treeview-menu">
                    <?php if(is_array($menu_list)): foreach($menu_list as $k=>$v): ?><li>
                            <?php if($v["controller_name"] == 'http' or $v["controller_name"] == 'https'): ?><a class="ajax-href" openType="frame" href='<?php echo ($v["controller_name"]); ?>://<?php echo ($v["action_name"]); ?>'><i class="fa <?php echo ($v["action_icon"]); ?>"></i><?php echo ($k); ?></a>
                                <?php else: ?>
                                <a class="ajax-href" openType="tab" href='<?php echo U($v["controller_name"]."/".$v["action_name"]);?>'><i class="fa <?php echo ($v["action_icon"]); ?>"></i><?php echo ($k); ?></a><?php endif; ?>
                        </li><?php endforeach; endif; ?>
                </ul>
        </section>
    </aside>
    <!--END Left Menu-->
    <section class="main-content right-side">
        <nav class="content-header">
            <h1>Welcome</h1>
            <div class="navbar-custom-menu pull-right" id="nav">
                <ul class="">
                    <li>欢迎：<?php echo ($user_info["name"]); ?></li>
                    <li class="message hide">
                        <a href="<?php echo U('message');?>" class="ajax-dialog show-message"
                          data-placement="left" data-original-title=''>
                            <i class="fa fa-envelope-o"></i><span class="badge hide">.</span></a>
                    </li>
                    <li>
                        <a href="<?php echo U('myedit');?>" class="ajax-dialog"
                           data-toggle="tooltip" data-placement="left" title="个人资料">
                            <i class="fa fa-user"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo U('editPassword');?>" class="ajax-dialog"
                           data-toggle="tooltip" data-placement="left" title="修改密码">
                            <i class="fa fa-key"></i></a>
                    </li>
                    <li>
                        <a href="/newapp/help.pdf" target="_blank"
                           data-toggle="tooltip" data-placement="left" title="帮助文档下载">
                            <i class="fa fa-question"></i></a>
                    </li>
                    <li>
                        <a href="<?php echo U('Public/logout');?>" data-toggle="tooltip" data-placement="left" title="退出"><i
                                class="fa fa-arrow-right"></i></a>
                    </li>

                </ul>
            </div>
        </nav>
        <div class="page-content">
            <p class="loading"><i class="fa fa-spinner fa-spin"></i> 加载中...</p>
        </div>
    </section>

</div>


<!-- jQuery 2.1.4 -->
<script src="/newapp/assets/vendor/jQuery/jQuery-2.1.4.min.js"></script>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/newapp/assets/vendor/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/newapp/assets/vendor/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="/newapp/assets/js/global.js"></script>
<script src="/newapp/assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="/newapp/assets/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/newapp/assets/vendor/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/newapp/assets/vendor/fastclick/fastclick.min.js" type="text/javascript"></script>
<script src="/newapp/assets/js/jquery.form.js" type="text/javascript"></script>
<script src="/newapp/assets/js/jquery.dialog.js" type="text/javascript"></script>
<script src="/newapp/assets/vendor/jquery-toastr/toastr.min.js"></script>
<script src="/newapp/assets/js/admin/app.js" type="text/javascript"></script>
<script src="/newapp/assets/js/admin/base.js?ver=20161124" type="text/javascript"></script>
<script src="/newapp/assets/js/litebox.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        $('.show-message').tooltip({'html':true,'show':100});
    });

</script>

</body>
</html>