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
                            <li>king1314ybq</li>
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
                <p><img src="/newapp/Public/web/img/icon_0004.png" alt=""> 公告通知&nbsp;&gt;&nbsp;<span class="message" style="color: rgb(169, 11, 22);">平台公告</span></p>
                <a href="http://www.shanxinhui.com/index/user/ydy.html">返回&gt;&gt;</a>
            </div>
            <ul class="tell">
                <li class="noticeA "><a href="#t1" class="active" id="news_list">平台公告</a></li>
                <li class="noticeB"><a href="#t2" id="news_list_person" class="">审核通知</a></li>
                <li class="noticeC"><a href="#t3" id="news_list_deblocking" onclick="getDeblockingList(12, 1, 1);" class="">解封通知</a></li>
            </ul>
            <table id="t1" class="actives">
                <thead>
                    <tr>
                        <td class="one">编号</td>
                        <td class="two">公告标题</td>
                        <td class="three">公告类型</td>
                        <td class="four">时间</td>
                    </tr>
                </thead>
                <tbody class="gonggaotongzhi"><tr><td>SXH172</td><td class="title"><span style="cursor:pointer" onclick="newsDetail(172)">春节期间工作安排</span></td><td>公告通知</td><td>2017-01-26 22:07:26</td></tr></tbody>
            </table>
            
            <div class="result_null" style="display: none;">暂无数据</div>

            
            <div class="tcdPageCode" id="callBackPager" style="text-align: center; display: none;"> 
                <ul class="pagination"> 
                    <li class="previous"><a href="#">«</a></li>
                    <li class="disabled hidden"><a href="#">...</a></li> 
                    <li class="active"><a href="#">1</a></li>
                    <li class="disabled hidden"><a href="#">...</a></li>
                    <li class="next"><a href="#">»</a></li>
                </ul>
            </div>
        </section>
        <footer class="footer">





            <footer class="footer">
                <p>©善心汇文化传播有限公司版权所有</p><span><a class="jingbei" href="http://www.miitbeian.gov.cn/">粤ICP备15076181号</a></span>
                <p>2016V2.0版本</p>
            </footer>


        </footer>