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
                        <li <?php if(($nav) == ""): ?>class="licur"<?php endif; ?>> <a href="/">首页</a></li>
                        <li <?php if(($nav) == "user"): ?>class="licur"<?php endif; ?>><a href="<?php echo U('User/index');?>">个人中心</a></li>
                        <li <?php if(($nav) == "news"): ?>class="licur"<?php endif; ?>><a href="<?php echo U('News/newsList');?>">系统公告</a></li>

                    </ul>
                </div>
            </div>
        </div>
<aside class="main">
    <div class="grzx">
    <span class="grtx"><img src="/Public/web/img/icon_10.png" alt=""></span>
    <span class="geren">个人中心</span>
    <a class="back" href="javscript:history.go(-1)">返回&gt;&gt;</a>
    </div>
    <section>
    <img src="/Public/web/img/icon_08.png" alt="">
    <a href="#"><img src="/Public/web/img/deblocking.png" alt=""></a>
    <a href="<?php echo U('User/userinfo');?>"><img src="/Public/web/img/icon_02.png" alt=""></a>
    <a href="<?php echo U('User/editPassword');?>"><img src="/Public/web/img/icon_03.png" alt=""></a>
    <a href="#"></a>
    <a href="#"></a>
    <a href="<?php echo U('User/recommend');?>"><img src="/Public/web/img/icon_06.png" alt=""></a>
    <a href="<?php echo U('User/register');?>"><img src="/Public/web/img/icon_07.png" alt=""></a>
    <span id="status_span" class="span1" style="padding-left: 8px;">已通过</span>

    <span class="fs" style="display:none"><i>4.0</i>&nbsp;分</span>
    <span class="span2">申请解封</span>
    <span class="span3 verify_status">查看资料</span>
    <span class="span4">修改密码</span>
<!--    <span class="span5">评价记录</span>
    <span class="span6">申领衣服</span>-->
    <span class="span7">推荐链接</span>
    <span class="span8">注册会员</span>
</section>
</aside>
<footer class="footer">
    <p>©有限公司版权所有</p> 　　　　<span><a class="jingbei" href="http://www.miitbeian.gov.cn/">粤ICP备15076181号</a></span>
    <p>2016V2.0版本</p>
</footer>
</body>
</html>