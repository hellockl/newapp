/**  *********************************
 * 个人中心 - 功德钱包
 * 转善心币、善种子
 * 提取管理奖
 * */


$(function(){
    // 个人中心功德钱包
    $.common.ajaxPost('/user/user/meritWallet' , {
        
    } , function(data) {
        if(data.errorCode == 0) {
            $(".shu1").html(data.result.activate_currency);
            $(".shu2").html(data.result.guadan_currency);
            $(".shu3").html(data.result.invented_currency);
            $(".shu4").html(data.result.manage_wallet);
            $("#company_manage_wallet").html(data.result.company_manage_wallet);
            $(".shu5").html(data.result.community_money_sum);
            $("#provide_current_money").val(data.result.provide_current_money);
            $("#order_taking").html(data.result.order_taking);
                
            if(parseInt(data.result.company_manage_wallet) > 0) {
                //企业管理奖模块，提取框
                $('.company_manage_wallet .glqb_tiqu').show();
                $('.company_manage_wallet').show();
                $('#company_manage_wallet').html(data.result.company_manage_wallet);
            }else{
                $(".company_manage_wallet").hide();
            }
        } else {
            layer.msg('钱包获取失败，请重新刷新页面');
        }
    });

    var flag,flag2,flag3,flag4,flag5,flag6,m,p,flag7= false;
    var mun2,mun3,moneys,yu_e = '';
    
    
    
    /** 转出善种子 点击弹出框
     * */
    $('.zhuanchu').click(function(){

    	//去掉----前面的提示没问题后,点击后失效
    	$("#zhongzi_btn").attr('disabled',false);
    	$("#zhongzi_btn").css('background','');
    	
        $(".gou").hide();
        $(".tishi_name").html('');
        $('.client_window_all').show();
        $('.zr').show(),
        $('.error>img').show();
        return false;
    })
    /** 转出善种子检测用户名 */
    $(".zr input[type=user]").blur(function(){
        if($(".zr input[type=user]").val()==''){
            $(".zr input[type=user]").css("border","1px solid #f00")
        }else{
            $.common.ajaxPost('/user/index/checkUserInfo' , {
                check_field:"username",
                value:$(".zr input[type=user]").val()
            } , function(data) {
                if(data.errorCode==201){
                    $(".gou").show();
                    $(".tishi_name").html(data.result.name).css('color','black');
                    return flag4 = true;
                }else{   
                   $(".tishi_name").html('接收人不存在！').css('color','red');
                   $(".gou").hide();
                   return flag4 = false;
                }
            });
        }
    })



    $(".zr .ipt1").blur(function(){
        var ipt1 = $(".zr .ipt1").val();

        var shu1 = $(".shu1").text();
        var admin = $(".zr input[type=user]").val();
        var username = $("#session_name").val();
      
        var mun = shu1-ipt1;
        if($.trim(ipt1) == ""){
            $(".zr .ipt1").css("border","1px solid #f00")
        }
        else{
            $(".tishi_szz").hide();
            if(ipt1>100){
            	if(username=='ztm' || username=='sxhadmin'){
            		if(ipt1 > 5000){
            			$(".zr em").html("<font color='red'>每次最多转出500个</font>")
                        $(".zr .ipt1").css("border","1px solid #f00")
                        return flag5 = false;
            		}else{
            			return flag5 = true;
            		}
            	}else{
            		$(".zr em").html("<font color='red'>每次最多转出100个</font>");
                    $(".zr .ipt1").css("border","1px solid #f00");
                    return flag5 = false;
            	}
                
            }else{
                if(mun<0){
                    $(".zr em").html("善种子余额不足")
                    $(".zr .ipt1").css("border","1px solid #f00")
                    return flag5 = false;
                }else{
                    return flag5 = true;
                }
            }
        }
    })
    // 二级密码（请输入二级密码）js
    $(".zr input[type=password]").blur(function(){
        if($(".zr input[type=password]").val()==""){
            //layer.msg("请输入二级密码");
            $(".zr input[type=password]").css("border","1px solid #f00")
        }else{
        	$('.tishi_ejmm').hide();
            return flag6 = true;
        }
    })
    // 备注 （请输入备注）js
    $(".zr .txt2").blur(function(){
        if($(this).val()==""){
           // layer.msg("请输入备注");
            $(this).css("border","1px solid #f00")
        }else{
        	$('.tishi_bb').hide();
            return flag7 = true;
        }
    })
    $(".zr .txt2").focus(function(){
        $(this).css("border","1px solid gray")
    })



    var transfer_active_currency = true;
    
    // -----------转出善种子 Ajax---------------------     
    function transferZhongziCurrency() {
        var money = $(".zr .ipt1").val();
        var zhongzi_token = $("#zhongzi_token").val();
        
        var encrypt = new JSEncrypt();
        var public_key = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvLSDII/m0La8yRolTzMZyoXRQ4CHgEZOhzpsfnWBlzF5eDXHiHlCQ8GgdJ6AMb0STFUQYU08XglexyZh0IlUSpCbQJ7IFb7SRk7JoKSDw6gKb/xxOiHx2bcfpLHjLRcNcWiLeV6bevoQlD/eLRmbybhlLeDtKLfpizQsftAjXaQIDAQAB-----END PUBLIC KEY-----';
        encrypt.setPublicKey(public_key);
        var pwd = encrypt.encrypt($(".zr input[type=password]").val());
        
        $.common.ajaxPost('/user/user/transferGuadanCurrency' , {
            receive_username:$(".zr input[type=user]").val(),
            password:pwd,
            income:money,
            info:$(".zr .txt2").val(),
            token:zhongzi_token,
            token_id:'zhongzi',
            currency_type:'activate_currency'
        } , function(data) { 

            //去掉----前面的提示没问题后,点击后失效
            $("#zhongzi_btn").attr('disabled',false);
            $("#zhongzi_btn").css('background','');

            if(data.errorCode==0){
                transfer_active_currency = true;
                layer.msg('转出成功！');
                setTimeout(function(){
//                        $('#activate_currency').html(parseInt($('#activate_currency').html())-parseInt(money));
                    window.location.href="/index/manager/userAccount.html"    
                },1000)
                $('.client_window_all').hide();
                $(".zr").hide();
                $(".error img").hide();
                $(".zr input").val("");
                //$(".txt2").val("");
                $(".name").val("");
                return flag4,flag5,flag6,flag7 = false;
            }else {
                $("#zhongzi_token").val(data.token);
                if(data.errorCode == 110){
                        setTimeout(function(){
                        window.location.href="/index/manager/userAccount.html"    
                    },1000);
                }else if(data.errorCode == -100){
                    layer.alert("您还未设置二级密码,马上前住设置",{icon:2,closeBtn:0},function(index){
                         location.href="/index/user/modifyPassword/type/2";
                    });
                }else if(data.errorCode == 30){
                    if(confirm("二级密码不能与登录密码一致！请确定前往修改二级密码。")){
                        window.location.href = '/index/user/modifyPassword/type/acc.html';
                    }else{
                        $("#jinbi_token").val(data.token);
                        transfer_guadan_currency = true;
                        $._transfer_shanxinbi_action_flag = true;
                    }
                    return false;
                }else{
                    transfer_active_currency = true;
                    layer.msg(data.errorMsg);
                    $._transfer_action_flag = true;
                }
                    
            }
        });
    }
    $(".zr .btn").click(function(){
 
    	var receive_username = $(".zr input[type=user]").val();
    	var password         = $(".zr input[type=password]").val();
    	var money            = $(".zr .ipt1").val();
    	var info             = $(".zr .txt2").val();
    	var zhongzi_token    = $("#zhongzi_token").val();
   
    	var szz_err = true;
    	 if($.trim(receive_username) == ''){
    		  $(".tishi_name").html("接收人账户不能为空。").show();
    		  szz_err = false;
              transfer_active_currency = true;
    	 }
    	
    	if($.trim(password) == ''){
  		  $(".tishi_ejmm").html("二级密码不能为空。").show();
  		  szz_err = false;
          transfer_active_currency = true
  	    }
    	
    	if($.trim(money) == ''){
    		  $(".tishi_szz").html("转出善种子不能为空。").show();
    		  szz_err = false;
              transfer_active_currency = true;
    	}
    	
    	if($.trim(info) == ''){
  		  $(".tishi_bb").html("备注不能为空。").show();
  		    szz_err = false;
            transfer_active_currency = true;
  	    }

        if(flag4 && flag5 && flag6 && szz_err ){
        	
            //前面的提示没问题后,点击后失效
            $("#zhongzi_btn").attr('disabled',true);
            $("#zhongzi_btn").css('background','#cecece');
            var kkk = layer.load();   
                
            checkIsTransferCurrency(receive_username , 1 , money , function(data) {
                layer.close(kkk);
                if(data.errorCode == 0) {
                    layer.open({
                        content : data.result.cur_msg,
                        btn:['确定'],
                        yes:function(index,layero) {
                            layer.close(index);
                            transferZhongziCurrency();
                        },
                        end:function() {
                            $("#zhongzi_btn").attr('disabled',false);
                            $("#zhongzi_btn").css('background','#a90b16');
                        }
                    });
//                    if(layer.confirm(data.result.cur_msg)) {
//                        transferZhongziCurrency();
//                    } else {
//                        $("#zhongzi_btn").attr('disabled',false);
//                        $("#zhongzi_btn").css('background','');
//                        return false;
//                    }
                } else {
                    transferZhongziCurrency();
                }
            });
 
 
        }
    })
    // 提示框样式
    $(".zr .ipt1").focus(function(){
        $(this).css("border","1px solid gray");
        $(".zr em").html("每次限100个");

    })
    $(".zr input[type=user]").focus(function(){
        $(this).css("border","1px solid gray")
    })
    $(".zr input[type=password]").focus(function(){
        $(this).css("border","1px solid gray")
    })

    $(".zh .ipt1").focus(function(){
        $(this).css("border","1px solid gray")
        $(".zh em").html("每次限300个")
    })
    $(".zh input[type=user]").focus(function(){
        $(this).css("border","1px solid gray")
    })
    $(".zh input[type=password]").focus(function(){
        $(this).css("border","1px solid gray")
    })

    $(".error img").click(function(){
        $('.client_window_all').hide();
        $(".zr").hide();
        $(".zh").hide();
        $(".error img").hide();
        $(".zr input").val("");
        $(".zh input").val("");
        $(".shanzhongzi").val("转出善种子");
        $(".shanzhongzi").attr("placeholder",'转出善种子');
        $(".shanxinbi").val("转出善心币")
        $(".shanxinbi").attr("placeholder",'转出善心币');
    })


    /** 
     * 转善心币 点击弹出框
     * 
     * */
    $('.sxb').click(function(){
    	//去掉----前面的提示没问题后,点击后失效
    	$("#jinbi_btn").attr('disabled',false);
    	$("#jinbi_btn").css('background','');
    	
        $(".gou2").hide();
        $(".name2").html('');
        $('.client_window_all').show();
        $('.zh').show()
        $('.error>img').show();
        return false;
    });
    
    var flag3 = false;
    var flag2 = false;
    var flag  = false;
    /** 善心币转出 检测用户名 */
    $(".zh input[type=user]").blur(function(){
        if($(".zh input[type=user]").val()==''){
            $(".zh input[type=user]").css("border","1px solid #f00")
        }else{
            $.ajax({
                url:"/user/index/checkUserInfo",
                type:"post",
                data:{
                    check_field:"username",
                    value:$(".zh input[type=user]").val()
                },
                success:function(data){
                    if(data.errorCode==201){
                        $(".gou2").show();
                        $(".name2").html(data.result.name).css('color','black'); 
                        return  flag = true;
                    }else{   
                       $(".name2").html('接收人不存在！').css('color','red');
                       $(".gou2").hide();
                       return flag = false;
                    }    
                }
            })
            return flag = true;
        }
    })


    $(".zh .ipt1").blur(function(){
        var ipt1 = $(".zh .ipt1").val();
        var shu2 = $(".shu2").text();
        var admin = $(".zr input[type=user]").val();
        var username = $("#session_name").val();
        var mun2 = shu2-ipt1;
        if(ipt1==''){
            $(".zh .ipt1").css("border","1px solid #f00")
        }
        else{
        	$(".tishi_zhsxb").hide();
            if(ipt1>300){
            	if(username=='ztm' || username=='sxhadmin'){
            		if(ipt1 > 5000){
            			$(".zh em").html("<font color='red'>每次最多转出5000个</font>")
                        $(".zh .ipt1").css("border","1px solid #f00")
                        return flag2 = false;
            		}else{
            			 return flag2 = true;
            		}
            	}else{
            		$(".zh em").html("<font color='red'>每次最多转出300个</font>");
                    $(".zh .ipt1").css("border","1px solid #f00");
                    return flag2 = false;
            	}

            }else{
                if(mun2<0){
                    $(".zh em").html("善心币余额不足")
                    $(".zh .ipt1").css("border","1px solid #f00")
                    return flag2 = false;
                }else{
                    return flag2 = true;
                }
            }
        }
    })

    //请输入二级密码 js
    $(".zh input[type=password]").blur(function(){
        if($(".zh input[type=password]").val()==""){
            //layer.msg("请输入二级密码");
        	
            $(".zh input[type=password]").css("border","1px solid #f00");
            return flag3 = false;
        }else{
        	$(".tishi_zhejmm").hide();
            return flag3 = true;
        }
    })
  //请输入备注 js
    $(".zh .txt2").blur(function(){
        if($(this).val()==""){
           // layer.msg("请输入备注");
            $(this).css("border","1px solid #f00");
            return flag7 = false;
        }else{
        	$(".tishi_zhbb").hide();
            return flag7 = true;
        }
    })

    $(".zh .txt2").focus(function(){
        $(this).css("border","1px solid gray")
    })
    
    
    
    
    
    /** 检测是否给此用户转过币
     * */
    function checkIsTransferCurrency(member_username , type , money , callback) {
        $.ajax({
            url : "/user/user/checkIsTransferCurrency",
            type : "post",
            data : {
                member_username : member_username ,
                type : type,
                income : money,
            },
            success : function(data) {
                callback(data);
            },
        });
//        $.common.ajaxPost("/user/user/checkIsTransferCurrency" , {
//            member_username : member_username ,
//            type : type,
//        } , function(data) {
//            callback(data);
//        });
    }
    
    
    
    
    
    
    
    /** 转出善心币
     * */
    function transferCurrency() {
        var money = $(".zh .ipt1").val();
        var jinbi_token      = $("#jinbi_token").val();
        var encrypt = new JSEncrypt();
        var public_key = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvLSDII/m0La8yRolTzMZyoXRQ4CHgEZOhzpsfnWBlzF5eDXHiHlCQ8GgdJ6AMb0STFUQYU08XglexyZh0IlUSpCbQJ7IFb7SRk7JoKSDw6gKb/xxOiHx2bcfpLHjLRcNcWiLeV6bevoQlD/eLRmbybhlLeDtKLfpizQsftAjXaQIDAQAB-----END PUBLIC KEY-----';
        encrypt.setPublicKey(public_key);
        var password = encrypt.encrypt($(".zh input[type=password]").val());
        
        $.common.ajaxPost('/user/user/transferGuadanCurrency' , {
                receive_username:$(".zh input[type=user]").val(),	
                password:password,
                income:money,
                info:$(".zh .txt2").val(),
                token:jinbi_token,
                token_id:'jinbi',
                currency_type:'guadan_currency'
        } , function(data) {
            //去掉----前面的提示没问题后,点击后失效
            $("#jinbi_btn").attr('disabled',false);
            $("#jinbi_btn").css('background','');

            if(data.errorCode==0){                	
                layer.msg('转出成功！');
                transfer_guadan_currency = true;
                setTimeout(function(){
//                        $('#guadan_currency').html(parseInt($('#guadan_currency').html())-parseInt(money));
                    window.location.href="/index/manager/userAccount.html"    
                },1000);
                $('.client_window_all').hide();
                $(".zh").hide();
                $(".error img").hide();
                $(".zh input").val("");
               // $(".txt2").val("");
                return flag3,flag2,flag,flag7 = false;
            }else if(data.errorCode == -100){
                layer.alert("您还未设置二级密码,马上前住设置",{icon:2,closeBtn:0},function(index){
                     location.href="/index/user/modifyPassword/type/2";
                });
            }
            else {
                $("#jinbi_token").val(data.token);
                if(data.errorCode == 110){
                    setTimeout(function(){
                        window.location.href="/index/manager/userAccount.html"    
                    },1000);
                }
                if(data.errorCode == 30){
                    if(confirm("二级密码不能与登录密码一致！请确定前往修改二级密码。")){
                        window.location.href = '/index/user/modifyPassword/type/acc.html';
                    }else{
                        $("#jinbi_token").val(data.token);
                        transfer_guadan_currency = true;
                        $._transfer_shanxinbi_action_flag = true;
                    }
                    return false;
                }else{
                    transfer_guadan_currency = true;
                    layer.msg(data.errorMsg);
                    $._transfer_shanxinbi_action_flag = true;
                }
            }
        });
    }
    var transfer_guadan_currency = true;
    $._transfer_shanxinbi_action_flag = true;
    var _guadan_currency_flag = false;
    $(".zh .btn").click(function(){
 
    	var receive_username = $(".zh input[type=user]").val();
    	var password         = $(".zh input[type=password]").val();
    	var money            = $(".zh .ipt1").val();
    	var info             = $(".zh .txt2").val();
    	
    	//
    	var sxb_err = true;
    	 if($.trim(receive_username) == ''){
    		  $(".tishi_zhname").html("接收人账户不能为空。").show();
    		  sxb_err = false;
              transfer_guadan_currency = true;
    	 }
    	
    	 if($.trim(money) == ''){
   		  $(".tishi_zhsxb").html("转出善种子不能为空。").show();
   		   sxb_err = false;
           transfer_guadan_currency = true;
         }
    	 
    	if($.trim(password) == ''){
  		  $(".tishi_zhejmm").html("二级密码不能为空。").show();
  		   sxb_err = false;
           transfer_guadan_currency = true;
  	    }
    	
    	
    	
    	if($.trim(info) == ''){
  		  $(".tishi_zhbb").html("备注不能为空。").show();
  		  sxb_err = false;
          transfer_guadan_currency = true;
  	    }
  
        if(flag3 && flag2 && flag && sxb_err){ 
        	//前面的提示没问题后,点击后失效
        	$(".btn").attr('disabled',true);
        	$(".btn").css('background','#cecece');
            /*if($._transfer_shanxinbi_action_flag != true) {
                transfer_guadan_currency = true;
                return false;
            }
            $._transfer_shanxinbi_action_flag = false;*/
            var eee = layer.load();
            checkIsTransferCurrency(receive_username , 2 , money , function(data) {
                layer.close(eee);
                if(data.errorCode == 0) {
                    layer.open({
                        content : data.result.cur_msg,
                        btn:['确定'],
                        yes:function(index,layero) {
                            layer.close(index);
                            transferCurrency();
                        },
                        end:function() {
                            $("#jinbi_btn").attr('disabled',false);
                            $("#jinbi_btn").css('background','');
                        }
                    });
                    
                } else {
                    transferCurrency();
                }
            });
        }
    })
    


    // 提取管理奖
    
    $('.glqb').click(function(){
//        layer.msg("正在更新数据，请稍候再使用");
       
    	//去掉----前面的提示没问题后,点击后失效
    	$("#gl_btn").attr('disabled',false);
    	$("#gl_btn").css('background','');
    	
        $('.client_window_all').show();
        $('.tiqu').show(),
            $('.fail>img').show();
        var shu = parseInt($(".shu4").html());
        var provide_current_money = $("#provide_current_money").val();
        $('.provide_current_currency').html(provide_current_money);
        $(".mey").html(shu);
    })

    $(".tiqu input[type=money]").blur(function(){
        var money = parseInt($(".tiqu input[type=money]").val());
        var shu4 = parseInt($(".manage_currency").html());
        var ye = shu4 - money;
        if(money==""){
            $(".tiqu input[type=money]").css("border","1px solid #f00")
        }else{
        	$(".tishi_tqmoney").hide();
            if(money > $("#manage_wallet").html()) {
                $(".tiqu input[type=money]").css("border","1px solid #f00");
                $(".bs").html('余额不足').css("color","#f00");
                return false;
            }
            if(money>499 && money%100==0 && money<=shu4){
                return m = true;
            } else{
                $(".tiqu input[type=money]").css("border","1px solid #f00")
                $(".bs").html('100的倍数，且大于或等于500').css("color","#f00")
            }
        }
    })
    $(".tiqu input[type=password]").blur(function(){
        if($(".tiqu input[type=password]").val()==""){
            $(".tiqu input[type=password]").css("border","1px solid #f00")
        }else{
        	$(".tishi_tqejmm").hide();
            return p = true;
        }
    })

    // 提取管理奖 Ajax
    $._current_money_action_flag = true;
    $(".ding").click(function(){
    	
        var money            = $(".tiqu input[type=money]").val();
        var password         = $(".tiqu input[type=password]").val();
        var gl_token         = $("#gl_token").val();
        var sxb_err = true;
        
		 if($.trim(money) == ''){
			  $(".tishi_tqmoney").html("提取领导奖不能为空。").show();
			  sxb_err = false;
		 }
		 
		 if($.trim(password) == ''){
			  $(".tishi_tqejmm").html("二级密码不能为空。").show();
			  sxb_err = false;
		 }
        
        
        if(p && m && sxb_err){
            /*if($._current_money_action_flag != true) {
                return false;
            }
            $._current_money_action_flag = false;*/
        	
        	//前面的提示没问题后,点击后失效
        	$("#gl_btn").attr('disabled',true);
        	$("#gl_btn").css('background','#cecece');
        	
            $.common.ajaxPost('/Home/User/myWallet' , {
                money:money,
                password:password,
                
            } , function(data) {
            	
            	//去掉----前面的提示没问题后,点击后失效
            	$("#gl_btn").attr('disabled',false);
            	$("#gl_btn").css('background','');
            	
                if(data.errorCode==0){
                   //console.log($(".tiqu input[type=password]").val())
                    layer.msg('提取管理奖成功！'); 
                    setTimeout(function(){
                        window.location.href = "/Home/User/myWallet.html";
                    },1500)
                    $('.client_window_all').hide();
                    $('.tiqu').hide();
                    $('.fail>img').css('display','none');
                }else{
                	if(data.errorCode == 110){
                		setTimeout(function(){
                          window.location.href="/index/manager/userAccount.html"    
                      },1000);
                	}else if(data.errorCode == 300040 || data.errorCode == 300036 || data.errorCode == 300100 ){
                		layer.open({
                			  title: '禁止提取',
                			  content: data.errorMsg,
                			  btn:'我知道了',
                			  btnClass:'#ccc',
                			});
                	}else{
                		layer.msg(data.errorMsg); 
                	}
                	
                	
                	
                	$("#gl_token").val(data.token);
                    
                    $._current_money_action_flag = true;
                }
            });
        }
    })
    
    
    
    /** 提取管理奖
     * */
    var tiqu_qiye_money_flag = false;
    var tiqu_qiye_password_flag = false;
    $('.glqb_tiqu').click(function() {
        $('.tiqu_qiye input').val('');
        $('.client_window_all').show();
        $('.tiqu_qiye').show();
        $('.fail>img').show();
        $(".gd_money").html($('#company_manage_wallet').html());
    });
    //提取金额判断
    $('.tiqu_qiye input[name="money"]').focus(function() {
        $(this).css('border','1px solid gray');
        $('.tiqu_qiye .bs_qiye').css('color','#999999');
    });
    $('.tiqu_qiye input[name="money"]').blur(function() {
        var money = $(this).val();
        if(money<500 || (parseInt(money)%100 != 0)) {
            $(this).css('border','1px solid red');
            $('.tiqu_qiye .bs_qiye').css('color','red');
            layer.msg("请按要求输入金额")
        } else {
            tiqu_qiye_money_flag = true;
        }
    });
    //二级密码判断
    $('.tiqu_qiye input[name="password"]').focus(function() {
        $(this).css('border','1px solid gray');
    });
    $('.tiqu_qiye input[name="password"]').blur(function() {
        if($(this).val() == '') {
            $(this).css('border','1px solid red');
        } else {
            tiqu_qiye_password_flag = true;
            $(this).css('border','1px solid gray');
        }
    });
    
    
//    /** 提取企业管理奖 提交 Ajax
//     * */
//    $('#qiye_click').click(function() {
//        if(tiqu_qiye_money_flag==true && tiqu_qiye_password_flag==true) {
//            var money = $('.tiqu_qiye input[name="money"]').val();
//            var payPassword = $('.tiqu_qiye input[name="password"]').val();
//            
//            $.common.ajaxPost('/user/manager/getCompanyManagerAward' , {
//                money : money,
//                payPassword : payPassword,
//            } , function(data) {
//                if(data.errorCode == 0) {
//                    layer.msg('提取成功');
//                    setTimeout(function() {
//                        $('.client_window_all').hide();
//                        $('.tiqu_qiye').hide();
//                        $('.fail>img').hide();
//                        
//                        $('#company_manage_wallet').html(parseInt($('#company_manage_wallet')-parseInt(money)));
//                    } , 1500);
//                } else {
//                    layer.msg(data.errorMsg);
//                }
//            });
//        }
//    });
// /** 提取企业管理奖 提交 Ajax
$("#qiye_click").click(function(){
    if($('.money').val()==""&&$(".money").val()==""){
        layer.msg("请输入提取金额或密码");
        return false;
    }else{
        var encrypt = new JSEncrypt();
        var public_key = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvLSDII/m0La8yRolTzMZyoXRQ4CHgEZOhzpsfnWBlzF5eDXHiHlCQ8GgdJ6AMb0STFUQYU08XglexyZh0IlUSpCbQJ7IFb7SRk7JoKSDw6gKb/xxOiHx2bcfpLHjLRcNcWiLeV6bevoQlD/eLRmbybhlLeDtKLfpizQsftAjXaQIDAQAB-----END PUBLIC KEY-----';
        encrypt.setPublicKey(public_key);
        var password = encrypt.encrypt($(".gd_password").val());
        data ={"password":password,"manageWallet":$(".money").val()};
        Management(data);
    }
})


function Management(d, u){
    var index = layer.load(0, { shade: false, });
    d = d ? d : {};
    u = u ? u : "/user/user/extractManageWallet";
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: d,
        url: u,
        async: true,
        success: function (e) {  
            layer.closeAll();
            if (e.errorCode == '0') {
             layer.msg("提取企业管理奖成功");
             $(".gd_password").val("");
             $(".money").val("");
            $('.tiqu_qiye').hide();
            $('.client_window_all').hide();
            $('.tiqu').hide(),
            $('.fail>img').css('display','none')
            setTimeout(function(){
                 window.location.href = "/index/manager/userAccount.html";
            },1500)
            }else{
                $(".gd_password").val("");
                layer.msg(e.errorMsg,{ icon: 5 })
                 
            }   
        },
        error: function (e) {
            $(".gd_password").val("");
            layer.msg("请求失败", { icon: 5 });
            layer.closeAll();

        }
    });

}

    

    $(".tiqu input[type=money]").focus(function(){
        $(".tiqu input[type=money]").css("border","1px solid gray")
        $(".bs").html('100的倍数，且大于或等于500').css("color","#999")
    })
    $(".tiqu input[type=password]").focus(function(){
        $(".tiqu input[type=password]").css("border","1px solid gray")
    })

    $('.fail>img').click(function(){
        $('.tiqu_qiye').hide();
        $('.client_window_all').hide();
        $('.tiqu').hide(),
        $('.fail>img').css('display','none')
    })

})
