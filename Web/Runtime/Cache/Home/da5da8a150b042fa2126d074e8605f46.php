<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
    <meta name="author">
    <title>个人中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Public/web/css/layer.css" id="layui_layer_skinlayercss" style="">
    <link rel="stylesheet" href="/Public/web/css/header.css">
    <link rel="stylesheet" href="/Public/web/css/ydy.css">
    <link rel="stylesheet" href="/Public/web/css/person.css">
    <style>
        footer {
            color: #535952;
            font-size: 15px;
            margin: 20px auto;
            text-align: center;
            width: 1220px;
        }
        .span1 {
            color: #fff;

        }
    </style>

</head>
<body>
<div class="header">
            <div class="head">
                <div class="head_l">
                    <ul>
                        <li><a href="/"><!--<img src="/Public/web/img/LOGO.png" height="100%">--></a></li>
                        <li><!--<img src="/Public/web/img/solgain.png" height="100%">--></li>
                    </ul>
                </div>
                <div class="head_r">
                    <div>
                        <ul><img src="/Public/web/img/newicon_7.png"></ul>
                        <ul class="username">
                            <li>会员:&nbsp;<?php echo ($user_info["user_name"]); ?></li>
                            <li>
                                <?php if($user_info["status"] == 0): ?><span class="status_header">待激活</span>
                                    <?php elseif($user_info["status"] == 1): ?>
                                    <span class="status_header">已激活</span><span class="verify_header">待审核</span>
                                    <?php else: ?>

                                    <span class="status_header">已激活</span><span class="verify_header">已通过</span><?php endif; ?>

                            </li>
                        </ul>
                    </div>
                    <div>|</div>
                    <div>
                        <ul>
                            <li><img src="/Public/web/img/icon_11.png"></li>
                            <a href="<?php echo U('Public/logout');?>"><li class="tc">退出系统</li></a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nav">
                <div class="sh">
                    <ul class="dh">
                        <li <?php if(($nav) == ""): ?>class="licur"<?php endif; ?>> <a href="/">首页</a></li>
                        <li <?php if(($nav) == "user"): ?>class="licur"<?php endif; ?>><a href="<?php echo U('User/index');?>">个人中心</a></li>
                        <li <?php if(($nav) == "news"): ?>class="licur"<?php endif; ?>><a href="<?php echo U('News/newsList');?>">系统公告</a></li>

                    </ul>

                </div>
            </div>
        </div>
<div>
    <span style="float: right;margin-right: 346px;">会员人数：<?php echo ($user_info["online_num"]); ?></span>
</div>


<aside class="main">
    <div class="grzx">
        <span class="grtx"><img src="/Public/web/img/icon_10.png" alt=""></span>
        <span class="geren">个人中心</span>
        <a class="back" href="javscript:history.go(-1)">返回&gt;&gt;</a>
    </div>
    <div>
        <div style="text-align: center">
            <img src="/Public/web/img/icon_08.png" alt="" style="padding-top: 30px;">
            <span  style="position: absolute; color: rgb(255, 255, 255); top: 410px; left: 49%;"><?php echo ($status_name); ?></span>

        </div>
        <div style="text-align: center">
            <a href="<?php echo U('User/userinfo');?>"><img src="/Public/web/img/icon_02.png" alt=""><span>个人信息</span></a>
            <a href="<?php echo U('User/editPassword');?>"><img src="/Public/web/img/icon_03.png" alt=""><span>修改密码</span></a>
            <a href="<?php echo U('User/recommend');?>"><img src="/Public/web/img/icon_06.png" alt=""><span>推荐推广</span></a>
            <a href="<?php echo U('User/register');?>"><img src="/Public/web/img/icon_07.png" alt=""><span>注册会员</span></a>
        </div>
    </div>
</aside>
<footer class="footer">
    <p>©聚金</p> 　　　
</footer>
</body>
</html>