<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Table</title>
	<link rel="stylesheet" href="/Public/plugins/layui/css/layui.css" media="all" />
	<link rel="stylesheet" href="/Public/css/global.css" media="all">
	<link rel="stylesheet" href="/Public/plugins/font-awesome/css/font-awesome.min.css">
</head>

<body>
<div class="admin-main">

	<!--<blockquote class="layui-elem-quote">-->
		<!--<button  class="layui-btn layui-btn-small add">-->
			<!--<i class="layui-icon">&#xe608;</i> 添加用户-->
		<!--</button>-->
	<!--</blockquote>-->
	<fieldset class="layui-elem-field">
		<legend>提供帮助列表</legend>
		<div class="layui-field-box">
			<table class="layui-table">
				<thead>
				<tr>
					<th>#</th>
					<th>用户名</th>
					<th>真实姓名</th>
					<th>手机号</th>
					<th>提供金额</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($givehelp_list)): foreach($givehelp_list as $k=>$vo): ?><tr>
						<td><?php echo ($k+1); ?></td>
						<td><?php echo ($vo["user_name"]); ?></td>
						<td><?php echo ($vo["name"]); ?></td>
						<td><?php echo ($vo["phone"]); ?></td>
						<td><?php echo ($vo["amount"]); ?></td>
						<td><?php echo ($vo["status_name"]); ?></td>
						<td>
							<a data="<?php echo ($vo["id"]); ?>" <?php if(($vo["status"]) == "0"): ?>class="layui-btn layui-btn-mini layui-btn-warm match"<?php else: ?>class="layui-btn layui-btn-mini layui-btn-disabled"<?php endif; ?> ><i class="layui-icon">&#xe64c;</i>匹配</a>
							<a data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-mini layui-btn-normal match_list"><i class="layui-icon">&#xe615;</i>查看匹配</a>
							<a data="<?php echo ($vo["id"]); ?>" <?php if(($vo["status"]) != "3"): ?>class="layui-btn layui-btn-mini layui-btn-waim confirm"<?php else: ?>class="layui-btn layui-btn-mini layui-btn-disabled"<?php endif; ?> ><i class="layui-icon">&#xe627;</i>确认打款</a>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
			<div class="page">
				<?php echo ($page); ?>
			</div>

		</div>
	</fieldset>

</div>
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

		$('.match').click(function(){
			var givehelp_id = $(this).attr('data');
			var url = "<?php echo U('Help/matching');?>";
			$.get(url,{givehelp_id:givehelp_id},function(data){
				if(data.status == 'error'){
					layer.msg(data.msg,{icon: 5});
					return;
				}
				layer.open({
					title:'匹配用户',
					offset:'t',
					type: 1,
					skin: 'layui-layer-rim', //加上边框
					area: ['50%','80%'], //宽高
					content: data,
				});
			})
		});
		$('.match_list').click(function(){
			var givehelp_id = $(this).attr('data');
			var url = "<?php echo U('Help/matchList');?>";
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
					area: ['80%','80%'], //宽高
					content: data,
				});
			})
		});
		$('.confirm').click(function(){
			var givehelp_id = $(this).attr('data');
			var url = "<?php echo U('Help/confirmMoney');?>";
			layer.confirm('您确定用户已打款吗?', {
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
</body>

</html>