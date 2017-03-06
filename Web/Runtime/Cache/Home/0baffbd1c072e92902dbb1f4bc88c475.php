<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="" lang="en">
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
    <!--<link rel="stylesheet" href="/Public/web/css/gdmx.css">-->
    <link rel="stylesheet" href="/Public/web/css/system.css">
    <link rel="stylesheet" href="/Public/web/css/bootstrap.css">
    <link rel="stylesheet" href="/Public/plugins/layui/css/layui.css" media="all" />
    <!--<link rel="stylesheet" href="/Public/css/global.css" media="all">-->
    <link rel="stylesheet" href="/Public/plugins/font-awesome/css/font-awesome.min.css">
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
        <section id="main" class="news_list_main">
            <div class="system">
                <p><img src="/Public/web/img/icon_010.png" alt=""> 帮助&nbsp;&gt;&nbsp;<span class="message" style="color: rgb(169, 11, 22);">帮助列表</span></p>
                <a href="javascript:history.go(-1)">返回&gt;&gt;</a>
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
                        <td class="four">操作</td>
                    </tr>
                </thead>
                <tbody class="gonggaotongzhi">
                <?php if(is_array($helplist)): $i = 0; $__LIST__ = $helplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($vo["id"]); ?></td>
                        <td class="title"><span style="cursor:pointer" onclick="newsDetail(172)"><?php echo ($vo["amount"]); ?></span></td>
                        <td><?php echo ($vo["today_sy"]); ?></td>
                        <td><?php echo ($vo["total_sy"]); ?></td>
                        <td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td>
                        <td><?php echo ($vo["status_name"]); ?></td>
                        <td>
                            <a data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-mini layui-btn-normal match_list"><i class="layui-icon">&#xe615;</i>查看匹配</a>
                            <a data="<?php echo ($vo["id"]); ?>" <?php if(($vo["status"]) != "2"): ?>class="layui-btn layui-btn-mini layui-btn-waim confirm"<?php else: ?>class="layui-btn layui-btn-mini layui-btn-disabled"<?php endif; ?> ><i class="layui-icon">&#xe627;</i>确认打款</a>
                        </td>
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
<script type="text/javascript" src="/Public/plugins/layui/layui.js"></script>
<script>
	layui.use(['laypage','layer','form'], function() {
		var laypage = layui.laypage,
				$ = layui.jquery
		//请求表单
//                laypage({
//                    cont: 'page'
//                    ,pages: 100 //总页数
//                    ,groups: 5 //连续显示分页数
//                });

		
		$('.match_list').click(function(){
			var givehelp_id = $(this).attr('data');
			var url = "<?php echo U('Index/matchList');?>";
			$.get(url,{givehelp_id:givehelp_id},function(data){
				if(data.status == 'error'){
					layer.msg(data.msg,{icon: 5});
					return;
				}
				layer.open({
					title:'匹配列表',
					offset:'t',
					type: 1,
					skin: 'layui-layer-rim', //加上边框
					area: ['70%','80%'], //宽高
					content: data,
				});
			})
		});
		$('.confirm').click(function(){
			var givehelp_id = $(this).attr('data');
			var url = "<?php echo U('Index/confirmMoney');?>";
			layer.confirm('您确定已经打款吗?', {
				icon: 3,
				skin: 'layer-ext-moon',
				btn: ['确认','取消'] //按钮
			}, function(){
				$.post(url,{givehelp_id:givehelp_id},function(data){
					if(data.status == 'error'){
						layer.msg(data.msg,{icon: 5});//失败的表情
						return;
					}else{
						layer.msg(data.msg, {
							icon: 6,//成功的表情
							time: 2000 //2秒关闭（如果不配置，默认是3秒）
						}, function(){
							location.reload();
						});
					}
				})
			});
		})


	});
</script>