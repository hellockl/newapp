<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,minimum-scale=1,maximum-scale=1">
<link rel="stylesheet" href="/newapp/Public/css/all.css">
<link rel="stylesheet" href="/newapp/Public/css/css.css">
<title></title>
</head>
<body class="page_login">
	<div class="all_boss loginback">
    	<div class="thebox">
        	<div class="title">会员登录</div>
            <form action="<?php echo U('checkLogin');?>" class="form" method="post" id="frm-login">
        	<ul class="theboxul locheck">
            	<li class="lodiv">
                	<span>登录账号：</span>
                    <input class="lo_nonull" type="text" name="user_name" placeholder="请输入账号" maxlength="18"/>
                    <span class="loisno all_none">*</span>
                </li>
                <li class="lodiv">
                	<span>登录密码：</span>
                    <input class="lo_nonull" type="password" name="password" placeholder="请输入密码" maxlength="18"/>
                    <span class="loisno all_none">*</span>
                </li>
                <li class="lodiv">
                	<span>验证码：</span>
                    <input class="lo_yzm" type="text" placeholder="" name="code" maxlength="4" id="loyzm"/>
                    <div class="yzmbox" id="code"><img id="imgVerify" style="cursor: pointer; width: 110px; height: 36px;" src="<?php echo U('verify');?>" onclick="flushVerify();" draggable="true"/></div>
                    <span class="loisno all_none">x</span>
                </li>
                <li>
                	<a href="javascript:;" class="u_btn u_btn_w117h30 u_btn_ghost" onClick="allobj.lobegincheck(this)">登录</a>
                    <a href="<?php echo U('register');?>" class="u_btn u_btn_w117h30 u_btn_ghost">注册</a>
                    <a href="#" class="all_hover">重置密码</a>
                </li>
            </ul>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        function flushVerify(){
            //重载验证码
            var url = "<?php echo U('verify');?>?r="+Math.floor(Math.random()*10000);
            $('#imgVerify').attr('src',url);
        }
    </script>
	<script src="/newapp/Public/js/jquery-3.1.1.min.js"></script>
	<!--<script src="/newapp/Public/js/vCode.js"></script>-->
	<script src="/newapp/Public/js/all.js"></script>
    <script src="/newapp/Public/js/js.js"></script>
</body>
</html>