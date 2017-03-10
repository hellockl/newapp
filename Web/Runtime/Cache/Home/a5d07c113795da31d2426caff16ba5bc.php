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

    <link rel="stylesheet" href="/Public/plugins/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="/Public/web/js/jquery-1.7.2.min.js"></script>
    <script src="/Public/web/js/layer.js"></script>
    <script src="/Public/web/js/common.js"></script>
    <script src="/Public/web/js/main.js"></script>
    <script src="/Public/web/js/system.js"></script>

    <style>
        #main1 table{width:1050px;text-align: center;margin-left: 87px;margin-right: 40px;font-size: 14px;}
        #main1 table td{width:285px;}
        /*#main td{padding:10px 80px;}*/
        #main1 td{padding:10px 0px;}
        #main1 table tr{border-bottom:1px dotted #aaa;padding:10px 10px;}
        /*#t1 tbody tr td:nth-child(1){width:150px;margin-left: 40px;position: relative;left:-25px;}*/
        #t1 tbody tr td:nth-child(1){margin-left: 40px;}
        /*#t1 tr td:nth-child(2){width:333px;text-align: left;}*/

        /*#t1 tr td:nth-child(3){width:250px;text-align: left;position:relative;left:-25px;}*/
        /*#t1 tr td:nth-child(4){width:410px;text-align: left;position:relative;left:-40px;}*/
        /*#main #t1 thead tr>td:nth-child(1){position:relative;left:-25px;}*/
        #main1 #t1 thead tr>td:nth-child(1){}
        #main1 #t1 thead tr>td:nth-child(2){position:relative;}
        /*#main #t1 thead tr>td:nth-child(3){position:relative;left:-10px;}*/
        /*#main #t1 thead tr>td:nth-child(4){position:relative;left:15px;}*/
        /*#main #t2 tr td:nth-child(1){width:100px;margin-left: 40px;}*/
        #t2 thead tr td,#t3 thead tr td{
            width: 381px;
        }
        #main #t2 thead tr>td:nth-child(3){position:relative;}
        /*.tell,table{padding:0 40px;}*/
        #t1 thead tr td,#t2 thead tr td,#t3 thead tr td{
            background-color:#eee;
        }
        #t1 thead tr,#t2 thead tr,#t3 thead tr{
            border:none;
        }


        .news_list_main {
            position:relative;
        }
        .news_list_main #callBackPager{
            position:absolute;
            bottom:0px;
            width:100%;
        }

        footer {
            color: #535952;
            font-size: 15px;
            margin: 20px auto;
            text-align: center;
            width: 1220px;
        }
        .u_btn {
            display: inline-block;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            letter-spacing: 1px;
            text-align: center;
            -webkit-transition: all 200ms;
            transition: all 200ms;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .u_btn_w300h50 {
            width: 300px;
            height: 50px;
            line-height: 50px;
        }
        .u_btn_rCC6633 {
    background-color: #CC6633;
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
    <span style="float: right;margin-right: 346px;">在线人数：<?php echo ($user_info["online_num"]); ?></span>
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
                <a href="<?php echo U('News/newsList');?>">
                    <img src="/Public/web/img/icon_003.png" alt="">
                </a>
                <br>
                <span>系统公告</span>
            </li>
            <li>
                <a href="<?php echo U('Index/givehelp');?>">
                    <img src="/Public/web/img/icon_011.png" alt="">
                </a>
                <br>
                <span>去提供帮助</span>
            </li>
            <li>
                <a href="<?php echo U('Index/gethelp');?>">
                    <img src="/Public/web/img/icon_011.png" alt="">
                </a>
                <br>
                <span>去得到帮助</span>
            </li>

        </ul>
    </div>
    
    <div class="gd">
        <div class="bs">
            <h2>提供帮助</h2>
            <p></p>　
            <?php if(($givecount) == "0"): ?><span class="not1" style="margin-left: 120px;">暂无提供帮助数据</span>
            <?php else: ?>
            <span class="not1" style="margin-left: 120px;">已提供：<?php echo ($give_help["givecount"]); ?>个帮助;总金额：<?php echo ($give_help["total_money"]); ?></span><?php endif; ?>
            <span id="proivde_money"></span> 　　
            <span id="proivde_status"></span>　　
            <span id="proivde_date"></span>
            
            <br>
            <a href="<?php echo U('Index/giveHelpList');?>">查看更多</a>

        </div>
        <div class="sz">
            <h2>获得受助</h2>
            <p></p>　　
            <?php if(($givecount) == "0"): ?><span class="not1" style="margin-left: 120px;">暂无受助的数据</span>
            <?php else: ?>
            <span class="not1" style="margin-left: 120px;">已得到：<?php echo ($get_help["getcount"]); ?>个帮助;总金额：<?php echo ($get_help["total_money"]); ?></span><?php endif; ?>
            <span id="accepthelp_money"></span>　　
            <span id="accepthelp_status"></span>　　
            <span id="accepthelp_date"></span>
            <br>
            <a href="<?php echo U('Index/getHelpList');?>">查看更多</a>

        </div>
    </div>
    <div class="clear"></div>

    <div id="main1" class="news_list_main">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;
            -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #e2e2e2 -moz-use-text-color -moz-use-text-color;
    border-image: none;
    border-style: solid none none;
    border-width: 1px medium medium;
    margin-left: 89px;
    margin-right: 84px;
    margin-top: 50px;
">
            <legend>最新公告</legend>
        </fieldset>
        <table id="t1" class="actives" >
            <thead>
            <tr>
                <td class="one">编号</td>
                <td class="two">公告标题</td>
                <td class="three">公告类型</td>
                <td class="four">时间</td>
            </tr>
            </thead>
            <tbody class="gonggaotongzhi">
            <?php if(is_array($newslist)): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td class="title"><span style="cursor:pointer" onclick="newsIndexDetail('<?php echo ($vo["id"]); ?>')"><?php echo ($vo["title"]); ?></span></td>
                    <td>系统公告</td>
                    <td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>

</div>
<footer class="footer">
    <p>©有限公司版权所有</p> 　　　　<span><a class="jingbei" href="http://www.miitbeian.gov.cn/">粤ICP备15076181号</a></span>
    <p>2016V2.0版本</p>
</footer>


</body>
</html>