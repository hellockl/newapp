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

			<blockquote class="layui-elem-quote">
				<button  class="layui-btn layui-btn-small add">
					<i class="layui-icon">&#xe608;</i> 新增资讯
				</button>
			</blockquote>

			<fieldset class="layui-elem-field">
				<legend>字段集</legend>
				<div class="layui-field-box">
					<form class="layui-form" method="post" action="<?php echo U('News/index');?>">
						<div class="layui-form-item">
							<label class="layui-form-label">标题</label>
							<div class="layui-input-block">
								<input type="text" name="title" lay-verify="title" value="<?php echo ($search["title"]); ?>" autocomplete="off" placeholder="请输入标题" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
							<label class="layui-form-label">范围选择</label>
							<div class="layui-input-inline">
								<input class="layui-input" placeholder="开始日" name="start_time" value="<?php echo ($search["start_time"]); ?>" id="LAY_demorange_s">
							</div>
							<div class="layui-input-inline">
								<input class="layui-input" placeholder="截止日" name="end_time" value="<?php echo ($search["end_time"]); ?>" id="LAY_demorange_e">
							</div>
						</div>
						<button class="layui-btn layui-btn-warm" lay-submit="" lay-filter="search">搜索</button>
					</form>
				</div>
			</fieldset>


			<fieldset class="layui-elem-field">
				<legend>资讯列表</legend>
				<div class="layui-field-box">
				<table class="layui-table">
					  <thead>
					    <tr>
					      <th>ID</th>
					      <th>缩略图</th>
					      <th>标题</th>
					      <th>内容</th>
					      <th>创建时间</th>
					      <th>更新时间</th><th>操作</th>
					    </tr>
					  </thead>
					  <tbody>
					  <?php if(is_array($news_list)): foreach($news_list as $k=>$vo): ?><tr>
					      <td><?php echo ($vo["id"]); ?></td>
					      <td>
							  <img src="<?php echo ($vo["smeta"]); ?>" width="50" height="50" /></td>
					      <td><?php echo ($vo["title"]); ?></td>
					      <td><?php echo ($vo["content"]); ?></td>
					      <td><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></td>
					      <td><?php echo (date("Y-m-d H:i:s",$vo["update_time"])); ?></td>
					      <td>
							<a data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-mini layui-btn-normal edit"><i class="layui-icon">&#xe642;</i>编辑</a>
							<a  data="<?php echo ($vo["id"]); ?>" class="layui-btn layui-btn-danger layui-btn-mini del"><i class="layui-icon">&#xe640;</i>删除</a>
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

				 $('.add').click(function(){
					var url = "<?php echo U('News/addNews');?>";
					$.get(url,function(data){
						if(data.status == 'error'){
							layer.msg(data.msg,{icon: 5});
							return;
						}
						layer.open({
							  title:'新增资讯',
							  type: 1,
							  skin: 'layui-layer-rim', //加上边框
							  area: ['90%','80%'], //宽高
							  content: data,
						});
					})
				 });
				//编辑用户
				$('.edit').click(function(){
					var news_id = $(this).attr('data');
					var url = "<?php echo U('News/editNews');?>";
					$.get(url,{news_id:news_id},function(data){
						if(data.status == 'error'){
							layer.msg(data.msg,{icon: 5});
							return;
						}
						layer.open({
							  title:'编辑用户',
							  type: 1,
							  skin: 'layui-layer-rim', //加上边框
							  area: ['90%','80%'], //宽高
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
					var news_id = $(this).attr('data');
					var url = "<?php echo U('News/deleteNews');?>";
					layer.confirm('确定删除吗?', {
						  icon: 3,
						  skin: 'layer-ext-moon',
						  btn: ['确认','取消'] //按钮
						}, function(){
							$.post(url,{news_id:news_id},function(data){
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
		<script>

//            layui.use('form','laydate', function(){
			layui.use(['form', 'layedit', 'laydate'], function(){
                var laydate = layui.laydate;
                var form = layui.form();

                var start = {
                    min: '2017-01-01 00:00:00'
                    ,max: '2099-06-16 23:59:59'
                    ,istoday: false
                    ,choose: function(datas){
                        end.min = datas; //开始日选好后，重置结束日的最小日期
                        end.start = datas //将结束日的初始值设定为开始日
                    }
                };

                var end = {
                    min: '2017-01-01 00:00:00'
                    ,max: '2099-06-16 23:59:59'
                    ,istoday: false
                    ,choose: function(datas){
                        start.max = datas; //结束日选好后，重置开始日的最大日期
                    }
                };

                document.getElementById('LAY_demorange_s').onclick = function(){
                    start.elem = this;
                    laydate(start);
                }
                document.getElementById('LAY_demorange_e').onclick = function(){
                    end.elem = this
                    laydate(end);
                }
                form.on('submit(search)', function(data){
                    layer.alert(JSON.stringify(data.field), {
                        title: '最终的提交信息'
                    })
                });
            });
		</script>
	</body>

</html>