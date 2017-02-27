<?php if (!defined('THINK_PATH')) exit();?><style>
  .layui-form-item{
    width: 100%;
  }
  .layui-form-item .layui-input-inline{
    width: 80%;
  }
</style>
<form class="layui-form">
  <div class="layui-form-item">
    <label class="layui-form-label">标题</label>
    <div class="layui-input-inline">
      <input type="text" name="title" lay-verify="required" value="<?php echo ($news_info["title"]); ?>" autocomplete="off"  id="title" class="layui-input">
    </div>
  </div>
  <input type="hidden" value="<?php echo ($news_info["id"]); ?>" name="id">
  <div class="layui-form-item">
    <label class="layui-form-label">缩略图</label>
    <div class="layui-input-inline">
      <input type="file" id='upload_img' name="file" class="layui-upload-file">
      <img src="<?php echo ($news_info["smeta"]); ?>" width="50" height="50" />
      <input type="hidden" name="smeta" id="smeta" readonly value="<?php echo ($news_info["smeta"]); ?>" class="layui-input">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">内容</label>
    <div class="layui-input-inline">
      <textarea id="content" name="content" style="display: none;"><?php echo ($news_info["content"]); ?></textarea>
      <script>
          layui.use('layedit', function(){
              var layedit = layui.layedit;
              //设置内容里面的上传接口
              layedit.set({
                  uploadImage: {
                      url: "<?php echo U('News/uploadImgForContent');?>" //接口url
                      ,type: 'post' //默认post
                  }
              });
              layedit.build('content'); //建立编辑器
          });
      </script>
    </div>
  </div>

  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="news">立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>
<script>
layui.use('form', function(){
	var form = layui.form(),
   		 $ = layui.jquery
	  //监听提交
	  form.on('submit(news)', function(data){
		  
	    var newsinfo = data.field;
        newsinfo.content = layui.layedit.getContent(1);
		var url = "<?php echo U('News/editNews');?>";
		$.post(url,newsinfo,function(data){
			if(data.status == 'error'){
				  layer.msg(data.msg,{icon: 5});//失败的表情
				  return;
			  }else if(data.status == 'success'){
				  layer.msg(data.msg, {
					  icon: 6,//成功的表情
					  time: 2000 //2秒关闭（如果不配置，默认是3秒）
					}, function(){
					   location.reload();
					}); 
			  }
		})
		
	    return false;//阻止表单跳转
	  });
	});

layui.use('upload', function(){
    $ = layui.jquery
    layui.upload({
        url: "<?php echo U('News/uploadImgForContent');?>"
        ,title: '重新上传图片'
        ,elem: '#upload_img' //指定原始元素，默认直接查找class="layui-upload-file"
        ,ext: 'jpg|png|gif|jpeg'
        ,method: 'post' //上传接口的http类型
        ,success: function(res){
            if(res.code == 300){
                layer.msg(res.msg);
            }else{
                $("#smeta").val(res.data.src);
            }
        }
    });
});
</script>