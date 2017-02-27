<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>   
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="robots" content="index,follow">
<meta content="telephone=no,email=no" name="format-detection">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="/newapp/Public/css/default.css">
<link rel="stylesheet" href="/newapp/Public/css/zi.css">
</head>
<body class="signin_bg" >
	<div id="page" class="sign_box">
		<!-- contents_STERT -->
		<div id="contents">
			<div id="main" class="clearboth">
					<div class="pagea ">
						<div class="prompt prompta">
							<img src="/newapp/Public/img/suc1.png">
							<span><?php echo ($message); ?></span>
							<p>页面自动<a href="<?php echo ($jumpUrl); ?>" id="href">跳转</a>等待时间:<em id="wait"><?php echo ($waitSecond); ?></em></p>
						</div>
						
					</div><!-- / #page -->
			</div><!-- / #main -->
		</div><!-- / #contents -->
	</div><!-- / #page -->
</body>
<script src="/newapp/Public/js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	(function(){
		var wait = document.getElementById('wait'),href = document.getElementById('href').href;
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time <= 0) {
				location.href = href;
				clearInterval(interval);
			};
		}, 1000);
	})();
</script>
</html>