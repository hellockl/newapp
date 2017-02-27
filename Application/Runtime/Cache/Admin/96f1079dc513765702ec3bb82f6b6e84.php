<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>Banner管理</title>
		<link rel="stylesheet" href="/Public/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" href="/Public/css/global.css" media="all">
		<link rel="stylesheet" href="/Public/plugins/font-awesome/css/font-awesome.min.css">
	</head>

	<body>
		<div class="admin-main">
		
			<blockquote class="layui-elem-quote">
				<button  data="0" class="layui-btn layui-btn-small add">
					<i class="layui-icon">&#xe608;</i> 添加Banner
				</button>
			</blockquote>
			<fieldset class="layui-elem-field">
				<legend>菜单列表</legend>
				<div class="layui-field-box">
				<table class="layui-table">
					  <thead>
					    <tr>
					      <th>ID</th>
					      <th>Banner名称</th>
					      <th>图片</th>
					      <th>添加时间</th>
					      <th>状态</th>
					      <th>管理</th>
					    </tr> 
					  </thead>
					  <tbody>
					  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							  <td><?php echo ($vo["id"]); ?></td>
							  <td><?php echo ($vo["banner_name"]); ?></td>
							  <td><img src="<?php echo ($vo["banner_img"]); ?>"> </td>
							  <td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td>
							  <td><?php echo ($vo["status"]); ?></td>
							  <td>
								  <a data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-mini layui-btn-normal edit"><i class="layui-icon">&#xe642;</i>编辑</a>
								  <a  data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-danger layui-btn-mini del"><i class="layui-icon">&#xe640;</i>删除</a>
							  </td>
						  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
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
			layui.use(['laypage','upload','layer','form'], function() {
				//var laypage = layui.laypage,
					$ = layui.jquery
					//请求表单
				 $('.add').click(function(){
					var id = $(this).attr('data');
					var url = "<?php echo U('Banner/addBanner');?>";
					$.get(url,{id:1},function(data){
						if(data.status == 'error'){
							layer.msg(data.msg,{icon: 5});
							return;
						}

						layer.open({
							  title:'添加Banner',
							  type: 1,
							  skin: 'layui-layer-rim', //加上边框
							  area: ['500px'], //宽高
							  content: data,
						});
					})
				 });
				
				//编辑菜单
				$('.edit').click(function(){
					var id = $(this).attr('data');
					var url = "<?php echo U('Banner/editBanner');?>";
					
					$.get(url,{id:id},function(data){
						if(data.status == 'error'){
							layer.msg(data.msg,{icon: 5});
							return;
						}
						
						layer.open({
							  title:'编辑Banner',
							  type: 1,
							  skin: 'layui-layer-rim', //加上边框
							  area: ['500px'], //宽高
							  content: data,
						});
					})
				 });
				
				//查看opt
				$('.see').click(function(){
					var id = $(this).attr('data');
					var url = "<?php echo U('Menu/viewOpt');?>";
					$.get(url,{id:id},function(data){
						if(data.status == 'error'){
							layer.msg(data.msg,{icon: 5});
							return;
						}
						layer.open({
							  title:'查看三级菜单',
							  type: 1,
							  skin: 'layui-layer-rim', //加上边框
							  area: ['1200px','500px'], //宽高
							  content: data,
						});
					})
				 });
				
				//删除
				$('.del').click(function(){
					var id = $(this).attr('data');
					var url = "<?php echo U('Banner/deleteBanner');?>";
					layer.confirm('确定删除吗?', {
						  icon: 3,
						  skin: 'layer-ext-moon',
						  btn: ['确认','取消'] //按钮
						}, function(){
							$.post(url,{id:id},function(data){
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