<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
    <meta name="author">
    <title>个人中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/newapp/Public/web/css/layer.css" id="layui_layer_skinlayercss" style="">
    <link rel="stylesheet" href="/newapp/Public/web/css/header.css">
    <link rel="stylesheet" href="/newapp/Public/web/css/ydy.css">
    <link rel="stylesheet" href="/newapp/Public/web/css/gdmx.css">
    
    <style>
        footer {
            color: #535952;
            font-size: 15px;
            margin: 20px auto;
            text-align: center;
            width: 1220px;
        }
    </style>
    <script type="text/javascript" src="/newapp/Public/web/js/jquery-1.7.2.min.js"></script>
<script src="/newapp/Public/web/js/layer.js"></script>
<script src="/newapp/Public/web/js/jquery_003.js"></script>
<script src="/newapp/Public/web/js/jquery.js"></script>
<script src="/newapp/Public/web/js/common.js"></script>
<script src="/newapp/Public/web/js/main.js"></script>
<!---->
<script type="text/javascript" src="/newapp/Public/web/js/donation.js" ></script>
<script src="/newapp/Public/web/js/regexp_sxh.js"></script>
</head>
    <body>
        <div class="center">
            <div class="content">
                <div class="header">
            <div class="head">
                <div class="head_l">
                    <ul>
                        <li><a href="http://www.shanxinhui.com/index/user/ydy.html"><!--<img src="/newapp/Public/web/img/LOGO.png" height="100%">--></a></li>
                        <li><!--<img src="/newapp/Public/web/img/solgain.png" height="100%">--></li>
                    </ul>
                </div>
                <div class="head_r">
                    <div>
                        <ul><img src="/newapp/Public/web/img/newicon_7.png"></ul>
                        <ul class="username">
                            <li><?php echo ($user_info["user_name"]); ?></li>
                            <li><span class="status_header">已激活</span><span class="verify_header">已通过</span></li>
                        </ul>
                    </div>
                    <div>|</div>
                    <div>
                        <ul>
                            <li><img src="/newapp/Public/web/img/icon_11.png"></li>
                            <a href="javascript:void(0);"><li class="tc">退出系统</li></a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nav">
                <div class="sh">
                    <ul class="dh">
                        <li><a href="http://www.shanxinhui.com/index/user/ydy.html?2017-02-13-02-14">首页</a></li>
                        <li><a href="javascript:;">个人中心</a></li>
                        <li><a href="javascript:;">系统公告</a></li>

                    </ul>
                </div>
            </div>
        </div>
                
                <div class="margin difficulty" style="">
                    <div>
                        <div class="difficulty_t">
                            <b class="fund"><img src="/newapp/Public/web/img/icon_0002.png" alt="">提供帮助&nbsp;&gt;&nbsp;<a href="#">提供资助</a></b>
                            <a href="/">返回 &gt;&gt;</a>
                        </div>
                        <div class="difficulty_c">
                            <h3>请填写帮助信息: </h3>
                            <div class="text">
                                <label>资助金额(元):</label>
                                <input class="utext" id="money" onblur="poor_c_utext(500000, 10000000, 10000)" name="username" type="text">
                                <s>*</s>
                                <span class="utext-msg">2000-20000</span>
                            </div>
                            <div class="pwd">
                                <label>二级密码:</label>
                                <input id="password" class="upwd" onblur="difficulty_c_upwd()" value="" name="userpwd" type="password">
                                <s>*</s>
                                <a href="http://www.shanxinhui.com/index/user/modifyPassword/type/acc.html" class="upwd-msg">忘记二级密码</a>
                            </div>
                            
                            <input id="provide_action_button" value="提供资助" class="button" onclick="difficulty_click(5, this)" type="button">
                        </div>
                        <div class="difficulty_b">
                            <span>简介:</span>
                            <p>ddddd</p>
                        </div>
                    </div></div>


                <input id="provide_token_id" value="650aa6f72de33de47372294ed8bc131f" type="hidden">
            </div>
        </div>
        <footer class="footer">


            <link rel="stylesheet" href="/newapp/Public/web/css/footer.css">


            <footer class="footer">
                <p>©善心汇文化传播有限公司版权所有</p><span><a class="jingbei" href="http://www.miitbeian.gov.cn/">粤ICP备15076181号</a></span>
                <p>2016V2.0版本</p>
            </footer>


        </footer>
    </body>
</html>