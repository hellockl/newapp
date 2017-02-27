<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="" lang="en">
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
    <!--<link rel="stylesheet" href="/newapp/Public/web/css/gdmx.css">-->
    <link rel="stylesheet" href="/newapp/Public/web/css/system.css">
    <link rel="stylesheet" href="/newapp/Public/web/css/bootstrap.css">
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
                        <li><a href="http://www.shanxinhui.com/index/user/ydy.html"><!--<img src="/newapp/Public/web/img/LOGO.png" height="100%">--></a></li>
                        <li><!--<img src="/newapp/Public/web/img/solgain.png" height="100%">--></li>
                    </ul>
                </div>
                <div class="head_r">
                    <div>
                        <ul><img src="/newapp/Public/web/img/newicon_7.png"></ul>
                        <ul class="username">
                            <li><?php echo ($user_name); ?></li>
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
        <section id="main" class="news_list_main">
            <div class="system">
                <p><img src="/newapp/Public/web/img/icon_010.png" alt=""> 帮助&nbsp;&gt;&nbsp;<span class="message" style="color: rgb(169, 11, 22);">帮助列表</span></p>
                <a href="http://www.shanxinhui.com/index/user/ydy.html">返回&gt;&gt;</a>
            </div>
            <ul class="tell">
<!--                <li class="noticeA "><a href="#t1" class="active" id="news_list">平台公告</a></li>
                <li class="noticeB"><a href="#t2" id="news_list_person" class="">审核通知</a></li>
                <li class="noticeC"><a href="#t3" id="news_list_deblocking" onclick="getDeblockingList(12, 1, 1);" class="">解封通知</a></li>-->
            </ul>
            <table id="t1" class="actives">
                <thead>
                    <tr>
                        <td class="one" width="10%">编号</td>
                        <td class="two">金额</td>
                        <td class="two">今日收益</td>
                        <td class="two">至今收益</td>
                        <td class="three">时间</td>
                        <td class="four">状态</td>
                    </tr>
                </thead>
                <tbody class="gonggaotongzhi">
                <?php if(is_array($helplist)): $i = 0; $__LIST__ = $helplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td>GHP<?php echo ($vo["id"]); ?></td>
                        <td class="title"><span style="cursor:pointer" onclick="newsDetail(172)"><?php echo ($vo["amount"]); ?></span></td>
                        <td>0</td>
                        <td>0</td>
                        <td><?php echo ($vo["create_time"]); ?></td>
                        <td>待确定</td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    
                    
                </tbody>
            </table>
            
            <div class="result_null" style="display: none;">暂无数据</div>

            
            <div class="tcdPageCode" id="callBackPager" style="text-align: center; display: block;"> 
                <?php echo ($show); ?>
<!--                <ul class="pagination"> 
                    <li class="previous"><a href="#">«</a></li>
                    <li class="disabled hidden"><a href="#">...</a></li> 
                    <li class="active"><a href="#">1</a></li>
                    <li class="disabled hidden"><a href="#">...</a></li>
                    <li class="next"><a href="#">»</a></li>
                </ul>-->
            </div>
        </section>
        <footer class="footer">





            <footer class="footer">
                <p>©#有限公司版权所有</p><span></span>
                <p>2017V1.0版本</p>
            </footer>


        </footer>