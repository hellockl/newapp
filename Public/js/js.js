
// 判断当前是哪一个页面，对应启动该页面需要的js方法
$(function(){
	allobj.init();
	var $body = $('body');
	if($body.hasClass('page_index')) {
		allobj.locheckInit();
		page_index();
	} else if ($body.hasClass('page_login')) {
		allobj.locheckInit();
		page_login();
	} else if ($body.hasClass('page_register')) {
		allobj.locheckInit();
		page_register();
	} else if ($body.hasClass('page_resetpwd')) {
		allobj.locheckInit();
		page_resetpwd();	
	} else if ($body.hasClass('page_return')) {
		page_return();	
	}
});

// 首页所需
function page_index() {
	// 点击枝干节点时，箭头方向改变
	$('.all_acc .all_branch').on('click', function(e){
		e.stopPropagation();
		var $t = $(this);
		var $ul = $t.find('ul');
		if ($ul.css('display') === 'none') {
			$ul.stop().slideDown(200);
			$t.children('img').css({ webkitTransform: 'rotate(90deg)', transform: 'rotate(90deg)' });
		} else {
			$ul.stop().slideUp(200);
			$t.children('img').css({ webkitTransform: 'rotate(180deg)', transform: 'rotate(180deg)' });
		}
	});
	
	// 点击叶子节点，切换到对应的子页
	$('.all_acc .all_leaf').on('click', function(e){
		var $t = $(this);
		var id = $t.find('span').data('leaf');
		e.stopPropagation();
		goToPage(id);
	});
	
	// 用户点击返回，回到对应的状态
	$('.all_accbody .back').on('click', function(){
		var $t = $(this);
		goToPage(pageHistory);
	});
	
	// 初始化复制粘贴插件
	var clipboard = new Clipboard('#zclip'); 

	clipboard.on('success', function(e) {
		console.info('Action:', e.action);
		console.info('Text:', e.text);
		console.info('Trigger:', e.trigger);
	alert("复制成功");
		e.clearSelection();
	});
	
	clipboard.on('error', function(e) {
		console.error('Action:', e.action);
		console.error('Trigger:', e.trigger);
	});
}

// 登录页所需
var logincode;
function page_login() {
	//初始化验证码
	// logincode = new vCode(document.getElementById("code"), {
	//	len: 4,
	//	bgColor: "#444444",
	//	colors: [
	//		"#DDDDDD",
	//		"#DDFF77",
	//		"#77DDFF",
	//		"#99BBFF",
	//		"#EEEE00"
	//	],
	//	onBack: function(id){
	//		console.log(id);
	//		$('#loyzm').data('yzm', id);
	//	}
	//});
	//$("#code").trigger("click");
}

function page_register(){
	
}

// 修改密码页所需
var resetcode;
function page_resetpwd(){
	//初始化验证码
	// resetcode = new vCode(document.getElementById("code"), {
	//	len: 4,
	//	bgColor: "#444444",
	//	colors: [
	//		"#DDDDDD",
	//		"#DDFF77",
	//		"#77DDFF",
	//		"#99BBFF",
	//		"#EEEE00"
	//	],
	//	onBack: function(id){
	//		console.log(id);
	//		$('#loyzm').data('yzm', id);
	//	}
	//});
	//$("#code").trigger("click");
}

// 操作成功页所需
function page_return(){
	timeToZero(10, null);
}

function timeToZero(time, url) {
	var time1 = time - 1;
	console.log(time1, url);
	if (time1 <= 0 ){
		if(url){location.href = url;}
	}
	$("#time").text(time1);
	setTimeout(function(){
		timeToZero(time1, url);
	},1000);
}

// 跳到对应的子页的通用方法
var pageHistory = 'sy';	// 记录上次点选的是哪一个页面 用于返回
var pageNow = 'sy';		// 记录当前点选的是哪一个页面 用于返回
function goToPage(id){
	console.log('goToPage:', id);
	var ids = id.split('_'); // 如果有下划线，表示是子页中的子页

	// 如果是子页中的子页，就改变其父级导航栏的状态
	if (ids.length <= 1) {
		$('.all_acc .check').removeClass('check');
		$('.all_acc span[data-leaf='+id+']').parent('li').addClass('check');
	} else {
		$('.all_acc .check').removeClass('check');
		$('.all_acc span[data-leaf='+ids[0]+']').parent('li').addClass('check');	
	}
	
	var $dom = $('.all_accbody>li[data-leaf='+id+']');
	if ($dom.css('display') === 'none') {
		$('.all_accbody>li').fadeOut(0);
		$dom.stop().fadeIn(200);
	}
	
	// 处理点击的历史记录
	pageHistory = pageNow;
	pageNow = id;
	// 如果切换到的是系统公告，底部的系统公告隐藏
	if (id === 'xtgg' || id === 'xtgg_xq') {
		$("#box_gg").fadeOut(0);	
	} else {
		$("#box_gg").fadeIn(0);
	}
}