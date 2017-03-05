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
    <style>
        footer {
            color: #535952;
            font-size: 15px;
            margin: 20px auto;
            text-align: center;
            width: 1220px;
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
                    <li><?php echo ($user_info["user_name"]); ?></li>
                    <li><span class="status_header">已激活</span><span class="verify_header">已通过</span></li>
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
                <li class="licur"><a href="/" >首页</a></li>
                <li><a href="<?php echo U('User/index');?>">个人中心</a></li>
                <li><a href="<?php echo U('News/index');?>">系统公告</a></li>

            </ul>
        </div>
    </div>
</div>

<div class="main">

    <div class="clear"></div>
    <div class="ttbb">
        <ul class="xtb">
            <li>
                <a href="<?php echo U('User/index');?>">
                    <img src="/Public/web/img/icon_05.png" alt="">
                </a>
                <br>
                <span>个人中心</span>
            </li>
            <li>
                <a href="<?php echo U('New/index');?>">
                    <img src="/Public/web/img/icon_003.png" alt="">
                </a>
                <br>
                <span>公告通知</span>
            </li>

        </ul>
    </div>
    <div class="gd">
        <div class="bs">
            <h2>提供帮助</h2>
            <p></p>　　
            <span class="not1" style="margin-left: 120px;">暂无布施中的数据</span>
            <span id="proivde_money"></span> 　　
            <span id="proivde_status"></span>　　
            <span id="proivde_date"></span>
            <br>
            <a href="<?php echo U('Index/giveHelpList');?>">查看更多</a>

        </div>
        <div class="sz">
            <h2>获得受助</h2>
            <p></p>　　
            <span style="margin-left: 120px;" class="not2">暂无接受中的数据</span>
            <span id="accepthelp_money"></span>　　
            <span id="accepthelp_status"></span>　　
            <span id="accepthelp_date"></span>
            <br>
            <a href="<?php echo U('Index/getHelpList');?>">查看更多</a>

        </div>
    </div>
</div>
<footer class="footer">
    <p>©有限公司版权所有</p> 　　　　<span><a class="jingbei" href="http://www.miitbeian.gov.cn/">粤ICP备15076181号</a></span>
    <p>2016V2.0版本</p>
</footer>


</body>
</html>