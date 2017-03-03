<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,minimum-scale=1,maximum-scale=1">
<link rel="stylesheet" href="/Public/css/all.css">
<link rel="stylesheet" href="/Public/css/css.css">
<title>首页</title>
</head>
<body class="page_index">
	<div class="all_boss">
    	<div class="box_title">
        	<img src="/Public/img/back.jpg" alt="" />
        </div>
        <ul class="box_userinfo">
        	<li class="all_nowarp">
            	<span>会员账号：</span>
                <span><?php echo ($user_id); ?></span>
            </li>
            <li class="all_nowarp">
            	<span>会员姓名：</span>
                <span><?php echo ($user_info["name"]); ?></span>
            </li>
            <li class="all_nowarp">
            	<span>注册时间：</span>
                <span><?php echo ($user_info["create_time"]); ?></span>
            </li>
        </ul>
        <div class="box_body all_clear">
        	<div class="l">
            	<ul class="all_acc">
                	<li class="all_leaf check"><span data-leaf="sy">首页</span></li>
                    <li class="all_branch">
                    	<span>个人中心</span>
                        <img class="all_trans" src="/Public/img/icon_r.png" alt="" />
                        <ul>
                        	<li class="all_leaf"><span data-leaf="zhxx">账户信息</span></li>
                            <li class="all_leaf"><span data-leaf="xgmm">修改密码</span></li>
                            <li class="all_leaf"><span data-leaf="hyzc">会员注册</span></li>
                            <li class="all_leaf"><span data-leaf="tglj">推广链接</span></li>
                        </ul>
                    </li>
                    <li class="all_branch">
                    	<span>财务管理</span>
                        <img class="all_trans" src="/Public/img/icon_r.png" alt="" />
                        <ul>
                        	<li class="all_leaf"><span data-leaf="zhmx">账户明细</span></li>
                            <li class="all_leaf"><span data-leaf="zcmx">支出明细</span></li>
                            <li class="all_leaf"><span data-leaf="srmx">收入明细</span></li>
                        </ul>
                    </li>
                    <li class="all_leaf" >
                    	<span data-leaf="xtgg">系统公告</span>
                    </li>
                </ul>
            </div>
            <div class="r">
            	<ul class="all_accbody">
                	<li data-leaf="sy" style="display:block;">
                    	<div class="div1">
                        	<a href="javascript:;" class="u_btn u_btn_w300h50 u_btn_rCC6633" onClick="goToPage('sy_tgbz')">提供帮助</a>
                            <a href="javascript:;" class="u_btn u_btn_w300h50 u_btn_b3366CC" onClick="goToPage('sy_hdbz')">获得帮助</a>
                            <a href="javascript:;" class="u_btn u_btn_w300h50 u_btn_rFF6666" onClick="goToPage('sy_shjg')">审核结果</a>
                        </div>
                        <div class="search">
                        	<span class="time">开始时间：<input id="d4311" class="Wdate" type="text" onFocus="WdatePicker()"/></span>
                            <span class="time">结束时间：<input id="d4312" class="Wdate" type="text" onFocus="WdatePicker()"/></span>
                            <span class="chose">
                            	<label><input type="radio" name="ended" value="完成" checked /><span> 完成</span></label>
                                <label><input type="radio" name="ended" value="未完成" /><span> 未完成</span></label>
                            </span>
                            <a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_white all_ml9">搜索</a>
                        </div>
                        <div class="table_box">
                        	<table class="table">
                            	<thead>
                                	<tr>
                                    	<td>ID</td>
                                        <td>类型</td>
                                        <td>金额</td>
                                        <td>打款账号</td>
                                        <td>姓名</td>
                                        <td>电话</td>
                                        <td>提交时间</td>
                                        <td>匹配情况</td>
                                    </tr>
                                </thead>
                                <tbody>
                                	<tr>
                                    	<td>1</td>
                                        <td>支出</td>
                                        <td>3000</td>
                                        <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                        <td>阿斯蒂芬</td>
                                        <td>13800000000</td>
                                        <td>2016-11-11</td>
                                        <td>完成</td>
                                    </tr>
                                    <tr>
                                    	<td>9999</td>
                                        <td>收入</td>
                                        <td>3000</td>
                                        <td>XXXXXXXX</td>
                                        <td>阿斯蒂2芬</td>
                                        <td>13800000000</td>
                                        <td>2016-11-11</td>
                                        <td>待确定</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li data-leaf="zhxx">
                    	<div class="boxone locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">账户信息</span>
                            </div>
                            <div class="infobox">
                            	<ul class="ul1">
                                	<li class="lodiv">
                                    	<span>手机号码:</span>
                                        <input class="lo_phone" type="text" maxlength="11" placeholder="请输入手机号码">
										<span class="loisno all_none">x</span>
                                    </li>
                                    <li>
                                    	<span>支付宝账号:</span>
                                        <input type="text" maxlength="50" placeholder="请输入支付宝账号">
                                    </li>
                                    <li class="lodiv">
                                    	<span>收货地址:</span>
                                        <input class="lo_nonull" type="text" maxlength="100" placeholder="请输入收货地址">
										<span class="loisno all_none">x</span>
                                    </li>
                                    <li>
                                    	<span>微信账号:</span>
                                        <input type="text" maxlength="50" placeholder="请输入微信账号">
                                    </li>
                                    <li class="lodiv">
                                    	<span>电子邮箱:</span>
                                        <input class="lo_email" type="text" maxlength="50" placeholder="请输入电子邮箱">
                                        <span class="loisno all_none">x</span>
                                    </li>
                                    <li>
                                    	<span>开户银行:</span>
                                        <input type="text" maxlength="50" placeholder="开户银行">
                                    </li>
                                    <li>
                                    	<span>居住城市:</span>
                                        <input type="text" maxlength="50" placeholder="居住城市">
                                    </li>
                                    <li>
                                    	<span>银行账号:</span>
                                        <input type="text" maxlength="50" placeholder="请输入银行账号">
                                    </li>
                                    <li class="lodiv">
                                    	<span>身份证号:</span>
                                        <input class="lo_id" type="text" maxlength="18" placeholder="请输入身份证号">
                                        <span class="loisno all_none">x</span>
                                    </li>
                                    <li>
                                    	<!-- 此标签的click方法调用了allobj.lobegincheck(this) 可以触发表单验证，如果成功，此方法会返回true -->
                                    	<a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="allobj.lobegincheck(this)">提交</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li data-leaf="xgmm">
                    	<div class="boxone locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">账户信息</span>
                            </div>
                            <div class="infobox">
                            	<ul class="ul2">
                                	<li class="lodiv">
                                    	<span>旧密码:</span>
                                        <input class="lo_pwd" type="password" maxlength="18" placeholder="请输入旧密码">
										<span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>新登录密码:</span>
                                        <input class="lo_pwd" type="password" maxlength="18" data-pwdr="a" placeholder="请输入新的登录密码">
                                        <span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>再次确认登录密码:</span>
                                        <input class="lo_pwdr" type="password" maxlength="18" data-pwdr="a" placeholder="请再次输入新的登录密码">
										<span class="loisno all_none">x 两次输入不一致</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>提款新密码:</span>
                                        <input class="lo_pwd" type="password" maxlength="18" data-pwdr="b" placeholder="请输入新的提款密码">
										<span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>再次确认提款新密码:</span>
                                        <input class="lo_pwdr" type="password" maxlength="18" data-pwdr="b" placeholder="请再次输入新的提款密码">
                                        <span class="loisno all_none">x 两次输入不一致</span>
                                    </li>
                                    <li>
                                    	<span>&nbsp;</span>
                                    	<!-- 此标签的click方法调用了allobj.lobegincheck(this) 可以触发表单验证，如果成功，此方法会返回true -->
                                    	<a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="allobj.lobegincheck(this)">提交</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li data-leaf="hyzc">
                    	<div class="boxone locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">新会员注册</span>
                            </div>
                            <div class="infobox">
                            	<ul class="ul1">
                                	<li class="lodiv">
                                    	<span>登录账号:</span>
                                        <input class="lo_pwd" type="text" maxlength="18" placeholder="请输入登录账号">
										<span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li>
                                    	<span>手机号码:</span>
                                        <input class="lo_phone" type="text" maxlength="11" placeholder="请输入手机号码">
                                        <span class="loisno all_none">x 手机号码不正确</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>登录密码:</span>
                                        <input class="lo_pwd" type="text" maxlength="18" data-pwdr="aa" placeholder="请输入登录密码">
										<span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>姓名:</span>
                                        <input class="lo_nonull" type="text" maxlength="18" placeholder="请输入真实姓名">
                                        <span class="loisno all_none">x 姓名不能为空</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>确定登录密码:</span>
                                        <input class="lo_pwdr" type="text" maxlength="50" data-pwdr="aa" placeholder="请再次输入登录密码">
                                        <span class="loisno all_none">x 两次输入不一致</span>
                                    </li>
                                    <li>
                                    	<span>推荐人账号:</span>
                                        <input type="text" maxlength="18" placeholder="请输入推荐人账号">
                                    </li>
                                    <li class="lodiv ul2li">
                                    	<span>提款密码:</span>
                                        <input class="lo_pwd" type="text" maxlength="18" data-pwdr="bb" placeholder="请输入提款密码">
										<span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li class="lodiv ul2li">
                                    	<span>确定提款密码:</span>
                                        <input class="lo_pwdr" type="text" maxlength="50" data-pwdr="aa" placeholder="请再次输入提款密码">
                                        <span class="loisno all_none">x 两次输入不一致</span>
                                    </li>
                                    <li>
                                    	<span>&nbsp;</span>
                                    	<!-- 此标签的click方法调用了allobj.lobegincheck(this) 可以触发表单验证，如果成功，此方法会返回true -->
                                    	<a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="allobj.lobegincheck(this)">提交</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li data-leaf="tglj">
                    	<div class="boxone">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">推广链接</span>
                            </div>
                            <div class="infobox all_clear">
                            	<div class="linkaddr all_nowarp">
                                	<span>链接地址:</span>
                                    <span id="zclipvalue">XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX3XXXX6X</span>
                                </div>
                                <button id="zclip" class="zclip u_btn u_btn_w150h30 u_btn_b3366CC" data-clipboard-action="copy" data-clipboard-target="#zclipvalue">点击复制</button>
                            </div>
                            <div class="mark">
                            	<img src="img/mark.jpg" alt="mark"/>
                            </div>
                        </div>
                    </li>
                    <li data-leaf="zhmx">
                    	<div class="boxone">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">账户明细</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <ul class="zhmxul">
                                    <li>
                                        <span>总共支出：100000<i>元</i></span>
                                        <a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="goToPage('zcmx')">查看详情</a>
                                    </li>
                                    <li>
                                        <span>总共收入：100000<i>元</i></span>
                                        <a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="goToPage('srmx')">查看详情</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li data-leaf="zcmx">
                    	<div class="boxone copyindex">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">支出明细</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <div class="search">
                                    <span class="time">开始时间：<input id="time1" class="Wdate" type="text" onFocus="WdatePicker()"/></span>
                                    <span class="time">结束时间：<input id="time2" class="Wdate" type="text" onFocus="WdatePicker()"/></span>
                                    <a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_white all_ml9">搜索</a>
                                </div>
                                <div class="table_box">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td>ID</td>
                                                <td>类型</td>
                                                <td>金额</td>
                                                <td>打款账号</td>
                                                <td>姓名</td>
                                                <td>电话</td>
                                                <td>提交时间</td>
                                                <td>匹配情况</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>支出</td>
                                                <td>3000</td>
                                                <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                                <td>阿斯蒂芬</td>
                                                <td>13800000000</td>
                                                <td>2016-11-11</td>
                                                <td><a href="javascript:;" onClick="goToPage('kxxq')">完成</a></td>
                                            </tr>
                                            <tr>
                                                <td>9999</td>
                                                <td>收入</td>
                                                <td>3000</td>
                                                <td>XXXXXXXX</td>
                                                <td>阿斯蒂2芬</td>
                                                <td>13800000000</td>
                                                <td>2016-11-11</td>
                                                <td><a href="javascript:;" onClick="goToPage('kxxq')">待确定</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- 收入明细 -->
                    <li data-leaf="srmx">
                    	<div class="boxone copyindex">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">收入明细</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <div class="search">
                                    <span class="time">开始时间：<input id="time3" class="Wdate" type="text" onFocus="WdatePicker()"/></span>
                                    <span class="time">结束时间：<input id="time4" class="Wdate" type="text" onFocus="WdatePicker()"/></span>
                                    <a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_white all_ml9">搜索</a>
                                </div>
                                <div class="table_box">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td>ID</td>
                                                <td>类型</td>
                                                <td>金额</td>
                                                <td>打款账号</td>
                                                <td>姓名</td>
                                                <td>电话</td>
                                                <td>提交时间</td>
                                                <td>匹配情况</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>支出</td>
                                                <td>3000</td>
                                                <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                                <td>阿斯蒂芬</td>
                                                <td>13800000000</td>
                                                <td>2016-11-11</td>
                                                <td><a href="javascript:;" onClick="goToPage('kxxq')">完成</a></td>
                                            </tr>
                                            <tr>
                                                <td>9999</td>
                                                <td>收入</td>
                                                <td>3000</td>
                                                <td>XXXXXXXX</td>
                                                <td>阿斯蒂2芬</td>
                                                <td>13800000000</td>
                                                <td>2016-11-11</td>
                                                <td><a href="javascript:;" onClick="goToPage('kxxq')">待确定</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li data-leaf="xtgg">
                    	<div class="boxone copyindex">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">系统公告</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <div class="table_box">
                                    <table class="table xtgg_table">
                                        <thead>
                                            <tr>
                                                <td>ID</td>
                                                <td>类型</td>
                                                <td>时间</td>
                                                <td>标题</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr onClick="goToPage('xtgg_xq')">
                                                <td>1</td>
                                                <td>系统公告</td>
                                                <td>2016-11-11</td>
                                                <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                            </tr>
                                            <tr onClick="goToPage('xtgg_xq')">
                                                <td>9999</td>
                                                <td>系统公告</td>
                                                <td>2016-11-11</td>
                                                <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- 系统公告详情页 -->
                    <li data-leaf="xtgg_xq">
                    	<div class="boxone copyindex">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;" data-return="sy">返回</a>
                                <span class="title">公告详情</span>
                            </div>
                            <div class="infobox all_clear all_tl newsbox">
                                <div class="title">公告标题公告标题公告标题公告标题公告标题公告标题公告标题公告标题公告标题公告标题公告标题</div>
                                <div class="time">时间：2016-11-11 17:00</div>
                                <div class="info">内内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容容</div>
                            </div>
                        </div>
                    </li>
                    <!-- 提供帮助页 -->
                    <li data-leaf="sy_tgbz">
                    	<div class="boxone copyindex locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;">返回</a>
                                <span class="title">提供帮助</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <ul class="ul2">
                                	<li class="lodiv">
                                    	<span>帮助金额:</span>
                                        <input class="lo_number" type="password" maxlength="18" placeholder="1000~20000元" data-number="1000-20000" />
										<span class="loisno all_none">x 请输入1000~20000之间的整数</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>登录密码:</span>
                                        <input class="lo_pwd" type="password" maxlength="18" data-pwdr="a" placeholder="请输入密码">
                                        <span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li>
                                    	<span>&nbsp;</span>
                                    	<!-- 此标签的click方法调用了allobj.lobegincheck(this) 可以触发表单验证，如果成功，此方法会返回true -->
                                    	<a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="allobj.lobegincheck(this)">提交帮助</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- 获得帮助页 -->
                    <li data-leaf="sy_hdbz">
                    	<div class="boxone copyindex locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;">返回</a>
                                <span class="title">获得帮助</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <ul class="ul2">
                                	<li class="lodiv">
                                    	<span>得到金额:</span>
                                        <input class="lo_number" type="password" maxlength="18" placeholder="1000~20000元" data-number="1000-20000" />
										<span class="loisno all_none">x 请输入1000~20000之间的整数</span>
                                    </li>
                                    <li class="lodiv">
                                    	<span>登录密码:</span>
                                        <input class="lo_pwd" type="password" maxlength="18" data-pwdr="a" placeholder="请输入密码">
                                        <span class="loisno all_none">x 6-18位字符</span>
                                    </li>
                                    <li>
                                    	<span>&nbsp;</span>
                                    	<!-- 此标签的click方法调用了allobj.lobegincheck(this) 可以触发表单验证，如果成功，此方法会返回true -->
                                    	<a href="javascript:;" class="u_btn u_btn_w150h30 u_btn_b3366CC" onClick="allobj.lobegincheck(this)">获得帮助</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                     <!-- 审核结果 -->
                    <li data-leaf="sy_shjg">
                    	<div class="boxone copyindex locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;">返回</a>
                                <span class="title">审核结果</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <table class="table">
                                	<thead>
                                    	<tr>
                                        	<td>ID</td>
                                            <td>审核时间</td>
                                            <td>审核结果</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
                                        	<td>1</td>
                                            <td>2016-11-11</td>
                                            <td>审核中</td>
                                        </tr>
                                        <tr>
                                        	<td>2</td>
                                            <td>2016-11-11</td>
                                            <td>审核中</td>
                                        </tr>
                                        <tr>
                                        	<td>3</td>
                                            <td>2016-11-11</td>
                                            <td>审核完成</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>
                     <!-- 确定收款 款项详情 -->
                    <li data-leaf="kxxq">
                    	<div class="boxone copyindex locheck">
                        	<div class="titlebox">
                            	<a class="back" href="javascript:;">返回</a>
                                <span class="title">款项详情</span>
                            </div>
                            <div class="infobox all_clear all_tl">
                                <table class="table table_little">
                                	<thead>
                                    	<tr>
                                        	<td>ID</td>
                                            <td>类型</td>
                                            <td>金额</td>
                                            <td>提交时间</td>
                                            <td>付款信息</td>
                                            <td>匹配信息</td>
                                            <td>付款账号</td>
                                            <td>姓名</td>
                                            <td>电话</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
                                        	<td>1</td>
                                            <td>提供帮助</td>
                                            <td>20000</td>
                                            <td>2016-11-11</td>
                                            <td>已付款，已提交</td>
                                            <td>完成</td>
                                            <td>XXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                            <td>小张小红</td>
                                            <td>13800000000</td>
                                        </tr>
                                        <tr>
                                        	<td>2</td>
                                            <td>提供帮助</td>
                                            <td>20000</td>
                                            <td>2016-11-11</td>
                                            <td>已付款，已提交</td>
                                            <td>完成</td>
                                            <td>XXXXXXXXXXXXXXXXXXXXXXXXX</td>
                                            <td>小张小红</td>
                                            <td>13800000000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div id="box_gg" class="box_gg">
        	<div class="title">系统公告</div>
            <table class="table xtgg_table">
            	<thead>
                    <tr>
                        <td>ID</td>
                        <td>类型</td>
                        <td>时间</td>
                        <td>标题</td>
                    </tr>
                </thead>
                <tbody>
                	<tr onClick="goToPage('xtgg_xq')">
                        <td>123</td>
                        <td>系统公告</td>
                        <td>2016-11-11</td>
                        <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                    </tr>
                    <tr onClick="goToPage('xtgg_xq')">
                        <td>123</td>
                        <td>系统公告</td>
                        <td>2016-11-11</td>
                        <td>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	<script src="/Public/js/jquery-3.1.1.min.js"></script>
    <script src="/Public/js/clipboard.min.js"></script>
	<script src="/Public/js/all.js"></script>
    <script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
    <script src="/Public/js/js.js"></script>
</body>
</html>