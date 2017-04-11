/**
 * Created by shanfubao on 2016/9/21.
 */

//出局钱包提取全部
function extract_all() {
    var community = $('#accepthelp_community_cid').val();
    var money = $('.accepthelp_community_money').html();
    $('#accepthelp_action_cid').val(community);
    $('#accepthelp_money').val(money);
}
//接单钱包提取全部
function extract_order_all() {
    var community = $('#accepthelp_order_taking_cid').val();
    var money = $('.accepthelp_order_taking_money').html();
    $('#accepthelp_action_cid').val(community);
    $('#accepthelp_money').val(money);
}

$(function(){
    $(".button").click(function(){
        var a = ($("#money").val());
        var b = ($(".sum").text());
        var pwd = $('.pwd').val();

        if(a=='' || a=='undefined') {
            layer.msg('请填写受助金额');
            return false;
        }
        if(parseInt(a)%100 != 0) {
            layer.msg('受助金额必须是100的整倍数');
            return false;
        }
        if(parseInt(a) <= 0) {
            layer.msg('请填写受助金额');
            return false;
        }
        if(parseInt(a) > parseInt(b)) {
//            layer.msg('钱包余额不足');
//            return false;
        }
        if(pwd=='' || pwd=='undefined') {
            layer.msg('请填写二级密码');
            return false;
        }
        var cid = $('input[name="cid"]').val();
        
        accepthelpAction(a , pwd , cid);
    });
    
    $(".pwd").focus(function(){
        $(".pwd").css("border","1px solid #ccc");
        $(".pwd-msg").html("忘记密码").css("color","#3c3c3c");
    })
    //$(".footer").load("footer.html");
    
    //获取钱包余额
//    function getMeritWallet() {
//        $.common.ajaxPost('/user/user/meritWallet',{
//
//        } , function(data) {
//            var html = "";
//            if(data.errorCode == 0){
//                $.each(data.result , function(i , o) {
//                    $('.currency_title').html(data.result[i+"_text"]);
//                    $(".sum").html(o);
//                    return false;
//                });
//            }else{
//                layer.msg(data.errorMsg);
//                setTimeout(function() {
//                    window.location.href = '/index/user/ydy.html';
//                } , 1500);
//            }
//        });  
//    }
    
    /** 查询各小区钱包（是否需要合并）
     * */
    $.common.ajaxPost('/user/community/getMergeAccountInfo' , {
        
    } , function(data) {
        if(data.errorCode == 1) {
            //您存在多个社区的接受资助金额，合并后才能感恩受助
            if(data.result !='' && data.result!=[]) {
                var html = "";
                for(var i in data.result) {
                    html += "<tr>\n\
                                <td>"+data.result[i]['community_text']+"</td>\n\
                                <td>"+data.result[i]['currency']+"</td>\n\
                                <td>"+data.result[i]['changetime']+"</td>"+
                            "</tr>";
                }
                $('.owe_merge .merge_list table tbody tr').remove();
                $('.owe_merge .merge_list table tbody').append(html);
                $('.owe_accepthelp').hide();
                $('.owe_merge').show();
            } else {
                
            }
        }
        if(data.errorCode == 0) {
            //无需合并社区的接受资助金额
            //获取接单钱包
//            getMeritWallet();
//            $('.currency_title').html(data.result.community_text);
            if(parseInt(data.result.order_taking) > 0) {
                $('.accepthelp_order_taking_money').html(data.result.order_taking);//接单钱包
                $('.order_taking_span').show();
            } else {
//                $('.order_taking_span').hide();
            }
            
            $('.accepthelp_community_money').html(data.result.currency);
            $('input[name="cid"]').val(data.result.cid);
            $('input[name="accepthelp_community_cid"]').val(data.result.cid);
            $('#accepthelp_money').val(data.result.currency);
        }
    });
    
    /** 确认合并
     * */
    $._merge_account_action_flag = true;
    $('#confirm').click(function() {
        if($._merge_account_action_flag != true) {
            return false;
        }
        $._merge_account_action_flag = false;
        $.common.ajaxPost('/user/community/mergeAccountAction' , {
            
        } , function(data) {
            if(data.errorCode == 1) {
                //您本轮尚未提取管理奖，提取后才能合并
                $("#shadows").fadeIn();
                $._merge_account_action_flag = true;
                return false;
            }
            if(data.errorCode == 0) {
                layer.msg('合并成功');
                setTimeout(function() {
//                    $('.currency_title').html(data.result.currency_text);
//                    $('.sum').html(data.result.money);
//                    $('.owe_merge').hide();
//                    $('.owe_accepthelp').show();
                    window.location.href = "/index/community/acceptList.html";
                } , 1500);
                $._merge_account_action_flag = true;
            } else {
                layer.msg('合并失败，请稍后再试');
                $._merge_account_action_flag = true;
            }
        });
    });
    /** 关闭确认合并弹出框
     * */
    $('.list_tcColse img').click(function() {
        $("#shadows").hide();
    });
    
});

$._flag_accepthelp_action_flag = true;
function accepthelpAction(money , pwd , cid) {
    var token = $("#accepthelp_token_id").val();
    //开始接受资助
    $(".button").attr("disabled",true);
    $(".button").css("background","#cecece");
    
    var encrypt = new JSEncrypt();
    var public_key = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvLSDII/m0La8yRolTzMZyoXRQ4CHgEZOhzpsfnWBlzF5eDXHiHlCQ8GgdJ6AMb0STFUQYU08XglexyZh0IlUSpCbQJ7IFb7SRk7JoKSDw6gKb/xxOiHx2bcfpLHjLRcNcWiLeV6bevoQlD/eLRmbybhlLeDtKLfpizQsftAjXaQIDAQAB-----END PUBLIC KEY-----';
    encrypt.setPublicKey(public_key);
    pwd = encrypt.encrypt(pwd);
    if(cid == 9){
    	accepthelpCreate(money , pwd , cid,token);
    }else{
	    $.ajax({
	    	url:"/user/manager/checkManageWallet",
	    	async:false,
	    	success:function(res){
                    if(res.errorCode == 0){
                        confim = layer.confirm("您尚未提取管理奖，如果放弃本次提取，只能到下提供资资助完成之后再提取。是否前往提取？",
                          {btn:['提交感恩受助','我要提取'],
                                btn1:function(index,layero){
                                    layer.close(confim);
                                    accepthelpCreate(money , pwd , cid,token);
                                },
                                btn2:function(index,layero){
                                    window.location.href="/index/manager/userAccount";
                                    return false;
                                },
                                cancel:function(){
                                    layer.close(confim);
                                    //accepthelpCreate(money , pwd , cid,token);
                                     $(".button").removeAttr("disabled");
                                     $(".button").css("background","#A90C17");
                                }
                        });
                    } else{
                        accepthelpCreate(money , pwd , cid,token);
                    }
	    	}
	    });
    }
}

function accepthelpCreate(money , pwd , cid,token){
	
	$.common.ajaxPost('/user/Community/accepthelpAction',{
        money:money,
        password:pwd,
        cid:cid,
        token : token,
        token_id : "accepthelp_action",
    },function(data) {
        var html = "";
//            if(data.errorCode == 1) {
//                layer.msg('您存在多个社区的接受资助金额，合并后才能感恩受助');
//                return false;
//            }
        if(data.errorCode == 0){
            layer.msg('感恩受助成功！');
            setTimeout(function() {
                window.location.href = '/index/user/help/list_type/acc.html';
            } , 1500);
//                $sum = $(".sum").html();
//                $(".sum").html(($sum -a));
        }else if(data.errorCode == -100){
            layer.alert("您还未设置二级密码,马上前住设置",{icon:2,closeBtn:0},function(index){
               location.href="/index/user/modifyPassword/type/2";
            });
        } else{
            if(data.errorCode == 30){
                if(confirm("二级密码不能与登录密码一致！请确定前往修改二级密码。")){
                    window.location.href = '/index/user/modifyPassword/type/acc.html';
                    return false;
                }
            }else{
                layer.msg(data.errorMsg);
                //alert("提交失败,"+data.errorMsg);
            }
        }
        $(".button").attr("disabled",false);
        $(".button").css("background","");
        $("#accepthelp_token_id").val(data.token);
    }); 
}