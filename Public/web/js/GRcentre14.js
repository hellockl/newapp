/** *************************************************** 
 * 个人中心 > 会员注册
 * ****************************************************
 * */


$(function() {
    var flag = flag2 = flag3 = flag4 = flag5 = flag6 = flag7 = false;
    
    
    // 账号名
    var reg1 =  $.regexp_sxh.regster_username;
	$('.p1 input').blur(function () {
        var v1 = $('.p1 input').val();
        var result = reg1.test(v1);
        if (!result) {
            $('.p1 .s1').html('* 账号不符，请按提示填写账号').css('color', 'red').show();
            $('.p1 input').css('border', '1px solid red');
			
            flag = false;
        }else{
            var username = $('.p1 input').val();
            $.Main.checkUsername(username , function(data) {
                if(data.errorCode == 201) {
                    $('.p1 input').css('border', '1px solid red');
                    $('.p1 .s1').html('帐户已存在').css('color', 'red').show();
                    flag = false;
                } else {
					$(".p1 .s1").html("<img src='/Public/web/img/true_1.png'/>").css('color','#d60616').show();
                    flag = true;
                }
            });
        }
    });
    $('.p1 input').focus(function () {
        var v1 = $('.p1 input').val();
        if (v1 = ' ') {
			$(".p1 .s1").hide();
			$(".p1 input").css("border","1px solid #666");
            //$('.p1 .s1').html('字母或数字字母组合').css(' font', '12px "microsoft yahei", Arial, Helvetica, sans-serif ').css('color','#666');
            //$('.p1 input').css('border', '1px solid #aaa');
        }
    })
    
    
    // 密码
    var reg2 = /(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,18}$/;
    $('.p2 input').blur(function () {
        var v2 = $('.p2 input').val();
        if(v2 == $('.p1 input').val()) {
            $('.p2 .s2').html('*  密码不能与帐号一致').css('color', 'red').show();
            return false;
        }
        
        var result = reg2.test(v2);
        if (!result) {
            $('.p2 .s2').html('*  密码不符，请按提示填写密码').css('color', 'red').show();
            $('.p2 input').css('border', '1px solid red');
        }else{
			$(".p2 .s2").html("<img src='/Public/web/img/true_1.png'>").css('color','#d60616').show();
            flag2 = true;
        }
    })
    $('.p2 input').focus(function () {
        var v2 = $('.p2 input').val();
        if (v2 = ' ') {
			$(".p2 .s2").hide();
			$(".p2 input").css("border","1px solid #666");
            //$('.p2 .s2').html('密码长度6-16位，数字字母、字符，至少包含两种').css(' font', '12px "microsoft yahei", Arial, Helvetica, sans-serif ').css('color','#666');
            //$('.p2 input').css('border', '1px solid #aaa');
        }
    })
    
    
    // 确认密码
    var reg3 = /^[\w]{6,16}$/;
    $('.p3 input').blur(function () {
        var v2 = $('.p2 input').val();
        var v3 = $('.p3 input').val();
        if (v3 !== v2||v3=="") {
            $('.p3 .s3').html('*  确认密码和登陆密码不一致').css('color', 'red').show();
            $('.p3 input').css('border', '1px solid red');
        }else{
			$(".p3 .s3").html("<img src='/Public/web/img/true_1.png'>").css('color','#d60616').show();
            flag3 = true;
        }
    })
    $('.p3 input').focus(function () {
        var v3 = $('.p3 input').val();
        if (v3 = ' ') {
			$(".p3 .s3").hide();
			$(".p3 input").css("border","1px solid #666");
            //$('.p3 .s3').html('确定密码和登录密码必须一致').css(' font', '12px "microsoft yahei", Arial, Helvetica, sans-serif ').css('color','#666');
            //$('.p3 input').css('border', '1px solid #aaa');
        }
    })
    
    
    // 姓名
    //svar reg4 = /^[\u4e00-\u9fa5]{1,10}$/;
    var reg4 = /^[^&^=^%^$^@^\)^\)^\~^\+^\[^\]^\}^\{^\<^\>^\*^\d]{2,80}$/i;
    $('.p4 input').blur(function () {
        var v4 = $('.p4 input').val();
        var result = reg4.test(v4);
        if (!result) {
            $('.p4 .s4').html('*  输入有误').css('color', 'red').show();
            $('.p4 input').css('border', '1px solid red');
        }else{
			$(".p4 .s4").html("<img src='/Public/web/img/true_1.png'>").css('color','#d60616').show();
            flag4 = true;
        }
    })
    $('.p4 input').focus(function () {
        var v4 = $('.p4 input').val();
        if (v4 = ' ') {
			$(".p4 .s4").hide();
			$(".p4 input").css("border","1px solid #666");
            //$('.p4 .s4').html('身份证上真实姓名').css(' font', '12px "microsoft yahei", Arial, Helvetica, sans-serif ').css('color','#666');
            //$('.p4 input').css('border', '1px solid #aaa');
        }
    })
    
    
    // 手机号码
    var reg5 = /^1[3578]\d{9}$/;
    var m=true;
    $('.p5 input').blur(function () {
        var v5 = $('.p5 input').val();
        var result = reg5.test(v5);
        if (!result) {
            $('.p5 .check').html('*  手机号码有误，请重新输入').css('color', 'red').show();
            $('.p5 input').css('border', '1px solid red');
        }else{
            var phone = $('.p5 input').val();
            $.Main.checkPhone(phone , function(data) {
                if(data.errorCode == 201) {
                    $('.p5 input').css('border','1px solid red');
                    $('#check_phone_text').html('手机号码已经被使用').css('color', 'red').show();
                    flag5 = false;
                } else {
					$(".p5 .check").html("<img src='/Public/web/img/true_1.png'>").css('color','#d60616').show();
                    flag5 = true;
                }
            });
            
        }
    })
    $('.p5 input').focus(function () {
        var v5 = $('.p5 input').val();
        if (v5 = ' ') {
			$(".p5 .check").hide();
			$(".p5 input").css("border","1px solid #666");
            //$('.p5 .check').html(' ');
           // $('.p5 input').css('border', '1px solid #aaa');
        }
    })
    

	 
    //弹出验证码框
    //$(".button").click(function(){
		//$(".patterning_modal input").val("");
		//$(".patterning_img img").attr("src","/captcha.html");
		//var c=parseInt(Math.random()*(1000-1)+1);
		//var src_now=$(".patterning_img img").attr("src")+"?"+c;
		//$(".patterning_img img").attr("src",src_now);
		//if(m){
		//	$(".patterning_code").show();
		//	$(".error > img").show();
		//}
    //});


	//关闭弹出框
	$(".error > img").click(function(){
		$(".patterning_code").hide();
	});

	//倒计时
	function times(){
		 var i = 299;
            var time = setInterval(function () {
                i--;
                if (i == 0) {
                    $(".p6 .button").html("点击重新发送")
                    m=true;
                    clearInterval(time);
                } else {
                    m = false
                    $(".p6 .button").html(i + "秒后重新发送")
                }
            }, 1000)
	}
    //如果手机号码正确，点击发送验证码
    var m = true;
    $(".queue_click").click(function () {
		//if($(".patterning_modal input").val()==""||$(".patterning_modal input").val()==undefined){
		//	layer.msg("验证码不能空");
		//	return false;
		//}
        if(flag!=true || flag2!=true || flag3!=true || flag4!=true) {
            layer.msg("请先填写完好信息");
            return false;
        }
        
        if(m == true && flag5 == true) {
            $.ajax({
                url:'/user/Smsverify/getSmsVerify',
                type:"POST",
                data:{
                    phone:$(".p5 input").val(),
                    length:6,
                    msg:"注册",
                    type:0,
					verify:$(".patterning_modal input").val(),
                    validTime:60
                },
                success:function(data){
                    if(data.errorCode==0){
                       $(".patterning_code").fadeOut(600);
						layer.msg(data.errorMsg);
						m  =  false;
						times();
                    }else{
						layer.msg(data.errorMsg);
					}
                }
            });
        }
    });  //button click




    // 验证码
    $(".p6 input").blur(function(){
        if($(".p6 input").val()==""){
            $(".p6 input").css('border', '1px solid red');
        }else{
            flag6 = true;
        }
    });
    $('.p6 input').focus(function () {
        var v5 = $('.p5 input').val();
        if (v5 = ' ') {
            $('.p6 .check').html(' ');
            $('.p6 input').css('border', '1px solid #aaa');
        }
    })


    // 推荐人账号
    var reg7 = /^[\w]{6,16}$/;
    //$('.p7 input').blur(function () {
    //    var v7 = $('.p7 input').val();
    //    var result = reg7.test(v7);
    //    if (!result) {
    //        $('.p7 .s7').html('* 账号不符，请按提示填写账号').css('color', 'red');
    //        $('.p7 input').css('border', '1px solid red');
    //    }else{
    //        flag7 = true;
    //    }
    //})
    $('.p7 input').focus(function () {
        var v7 = $('.p7 input').val();
        if (v7 = ' ') {
            //$('.p7 .s7').html('注册必须有推荐人 ').css(' font', '12px "microsoft yahei", Arial, Helvetica, sans-serif ').css('color','#666');
            $('.p7 input').css('border', '1px solid #aaa');
        }
    });


    if($(".p7 input").val() != '') {
        flag7 = true;
    }
    var flag_text = "请正确填写帐号";
    var flag2_text = "请正确填写帐号";
    var flag3_text = "请正确填写帐号";
    var flag4_text = "请正确填写帐号";
    var flag5_text = "请正确填写帐号";
    var flag6_text = "请正确填写帐号";
    var flag7_text = "请正确填写帐号";
    
    $._register_action_flag = true;
    $("#btn").click(function(){

        if( flag && flag2 && flag3 && flag4 && flag5 ){
            if($._register_action_flag != true) {
                return false;
            }
            $._register_action_flag = false;
            $.common.ajaxPost('register' , {

                user_name:$(".p1 input").val(),
                password:$(".p2 input").val(),
                repassword:$(".p3 input").val(),
                name:$(".p4 input").val(),
                phone:$(".p5 input").val(),
                referee_name:$(".p7 input").val()
            } , function(data) {
                if(data.errorCode == 0){
                  layer.msg("注册成功");
                  setTimeout(function() {
                      location.reload();
                  } , 1500);
                  $._register_action_flag = true;
                } else {
                    layer.msg(data.errorMsg);
                    $._register_action_flag = true;
                }
            });
        } else {
            layer.msg('请正确填写信息');
        }
    });

});//结束标签


  












