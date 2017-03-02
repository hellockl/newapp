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
							<?php if(($vo["status"]) == "0"): ?><a data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-mini layui-btn-normal match"><i class="layui-icon">&#xe642;</i>匹配</a>
								<?php else: ?>
								<a data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-mini layui-btn-normal match_list"><i class="layui-icon">&#xe642;</i>查看匹配</a><?php endif; ?>

						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>

		</div>
	</fieldset>
	<div class="admin-table-page">
		<div id="page" class="page">
			<?php echo ($page); ?>
		</div>
	</div>
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
					area: ['60%','60%'], //宽高
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
					area: ['60%','60%'], //宽高
					content: data,
				});
			})
		});
		//编辑用户
		$('.edit').click(function(){
			var user_id = $(this).attr('data');
			var url = "<?php echo U('Users/editUsers');?>";
			$.get(url,{user_id:user_id},function(data){
				if(data.status == 'error'){
					layer.msg(data.msg,{icon: 5});
					return;
				}
				layer.open({
					title:'编辑用户',
					type: 1,
					skin: 'layui-layer-rim', //加上边框
					area: ['500px'], //宽高
					content: data,
				});
			})
		});

		//分配角色
		$('.role').click(function(){
			var user_id = $(this).attr('data');
			var url = "<?php echo U('AuthGroup/giveRole');?>";
			$.get(url,{user_id:user_id},function(data){
				if(data.status == 'error'){
					layer.msg(data.msg,{icon: 5});
					return;
				}

				layer.open({
					title:'分配角色',
					type: 1,
					skin: 'layui-layer-rim', //加上边框
					area: ['500px','500px'], //宽高
					content: data,
				});
			})
		});

		//删除
		$('.del').click(function(){
			var user_id = $(this).attr('data');
			var url = "<?php echo U('User/deleteUser');?>";
			layer.confirm('确定删除吗?', {
				icon: 3,
				skin: 'layer-ext-moon',
				btn: ['确认','取消'] //按钮
			}, function(){
				$.post(url,{user_id:user_id},function(data){
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