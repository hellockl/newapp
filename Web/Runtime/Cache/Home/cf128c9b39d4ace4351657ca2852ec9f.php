<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
    <meta name="author">
    <title>个人中心-修改密码</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/newapp/Public/web/css/layer.css" id="layui_layer_skinlayercss" style="">
    <link rel="stylesheet" href="/newapp/Public/web/css/header.css">
    <link rel="stylesheet" href="/newapp/Public/web/css/ydy.css">
    <link rel="stylesheet" href="/newapp/Public/web/css/recompose.css">
    <script type="text/javascript" src="/newapp/Public/web/js/jquery-1.7.2.min.js"></script>
    <script src="/newapp/Public/web/js/layer.js"></script>
    <script src="/newapp/Public/web/js/regexp_sxh.js"></script>
    <script src="/newapp/Public/web/js/recompose.js"></script>
    <script src="/newapp/Public/web/js/regexp_sxh.js"></script>
    

</head>
<body>
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
                <li><a href="/">首页</a></li>
                <li class="licur"><a href="javascript:;">个人中心</a></li>
                <li><a href="javascript:;">系统公告</a></li>

            </ul>
        </div>
    </div>
</div>
<div id="center">
    <div class="header_01">
        <img src="/newapp/Public/web/img/user.png">
        <span class="spa1">个人中心 &gt;</span>
        <span class="spa2">修改密码</span>
        <a href="javascript:go(-1)"><span class="spa3">返回&gt;&gt;</span></a>
    </div>
    <div class="inside">
        <div class="psd">
            <ul>
                <li class="login_pas" style="color: rgb(233, 158, 30); border-bottom: 3px solid rgb(233, 158, 30);">登录密码</li>
                <li class="secondary_pas" style="color: rgb(51, 51, 51); border-bottom: 1px solid transparent;">二级密码</li>
            </ul>
        </div>
        <div class="psd_02">
            <div class="content_01" style="display: block;">
                <br>
                <div class="one_03">
                    <b class="b_07">手机号码不能为空</b>
                    <b class="b_08">手机号码有误请重新输入</b>

                    <input id="username" name="username" value="king1314ybq" type="hidden">
                </div>
                <div class="one">
                    <span><b>*</b>原登录密码：</span><input placeholder="请输入原登录密码" type="password">
                    <b class="b_01">原密码不能为空</b>
                    <b class="b_02">原密码错误</b>
                </div>
                <div class="one_01">
                    <span><b>*</b>新登录密码：</span><input placeholder="密码长度6-16位至少包含数字字母符号中两种" type="password">
                    <b class="b_03">新密码不能为空</b>
                    <b class="b_04">新密码不符要求，请按提示输入</b>
                </div>
                <div class="one_02">
                    <span><b>*</b>确认新登录密码：</span><input placeholder="确认两次密码输入一致" type="password">
                    <b class="b_05">确认密码不能为空</b>
                    <b class="b_06">两次密码输入不一致请重新输入</b>
                </div>
                <div class="one_04">
                    <span><b>*</b>手机号码：</span><input disabled="true" class="mobile_phone" value="137*****611" type="text"><br>

                </div>
                <div class="one_05">
                    <span><b>*</b>验证码：</span><input style="width:134px;" maxlength="6" id="password_verify" placeholder="请输入验证码" type="text"><span class="yzm" id="get_verify_code">获取验证码</span>
                </div>
                <p class="next_01">提交</p>

                <div class="patterning_code">
                    <div class="patterning_modal">
                        <span class="queue_yan">图形验证</span><br>
                        <input placeholder="请输入图形验证码" type="text">
                        <div class="patterning_img"><img src="password_files/captcha.png" alt="点击更换" onclick="this.src='/captcha.html?id='+Math.random();"></div>
                        <span class="queue_click">确定</span>
                    </div>
                    <span class="error"><img src="password_files/kuang_07.png" alt=""></span>
                </div>
            </div>

            <div class="content_02" style="display: none;">
                <br>
                <div class="tow_03">
                    <b class="b_15">手机号码不能为空</b>
                    <b class="b_16">手机号码有误请重新输入</b>

                </div>
                <div class="tow_01">
                    <span><b>*</b>新二级密码：</span><input placeholder="密码长度6-16位至少包含数字字母符号中两种" type="password">
                    <b class="b_11">新二级密码不能为空</b>
                    <b class="b_12">新二级密码不符要求，请按提示输入</b>
                </div>
                <div class="tow_02">
                    <span><b>*</b>确认新二级密码：</span><input placeholder="确认两次密码输入一致" type="password">
                    <b class="b_13">确认密码不能为空</b>
                    <b class="b_14">两次密码输入不一致请重新输入</b>
                </div>
                <div class="tow_05">
                    <span><b>*</b>手机号码：</span><input disabled="true" class="mobile_phone" value="137*****611" type="text"><br>

                </div>
                <div class="tow_06">
                    <span><b>*</b>验证码：</span><input style="width:134px;" id="secondary_password_verify" placeholder="请输入验证码" type="text"><span class="yzm  yzm_two">获取验证码</span>
                </div>
                <p class="next_02">提交</p>

                <div class="patterning_code1">
                    <div class="patterning_modal1">
                        <span class="queue_yan1">图形验证</span><br>
                        <input placeholder="请输入图形验证码" type="text">
                        <div class="patterning_img1"><img src="password_files/captcha.png" alt="点击更换" onclick="this.src='/captcha.html?id='+Math.random();"></div>
                        <span class="queue_click1">确定</span>
                    </div>
                    <span class="error1"><img src="password_files/kuang_07.png" alt=""></span>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <p>©有限公司版权所有</p> 　　　　<span><a class="jingbei" href="http://www.miitbeian.gov.cn/">粤ICP备15076181号</a></span>
    <p>2016V2.0版本</p>
</footer>
</body>
</html>