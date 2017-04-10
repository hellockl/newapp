/********************************
 * 布施积德 - 提供资助列表
 * 提供资助
 * @returns {undefined}
 */
//$(function(){
//    $.common.ajaxPost('/user/Community/getCommunityList',{
//
//    } , function(data) {
//        var html = "";
//        $.each(data.result.list,function(i,o){
//             var temp = $("#none").clone();
//             var html=temp.html();
//             html=html.replace(/\{a\}/g,o.name);
//             html=html.replace(/\{b\}/g,o.message);
//             html=html.replace(/\{c\}/g,o.id);
//             html=html.replace(/\{img\}/g,o.image);
//             // $("#none").children(":first").attr('src',o.image);
//             $(".main_b").append("<div>"+html+"</div>")
//        });
//    });
//}) //函数结束标签



// 鼠标移入隐藏框显示  为什么下标一直为0 2 4 6 8 10 这个不清楚以后再讨论 
 function pop(mun){
    var index = mun*2-2;
    $(".main_b div").eq(index).find("span").children("div").stop().animate({"top":"-206px"},300);
    // $(".main_b div .pop").children("div").stop().animate({"top":"-205px"},300);
    $(".pop").css("overflow","visible");
};
// 鼠标移出隐藏框隐藏
function out(){
    $(".pop .model").stop().animate({"top":"45px"},300);
    $(".pop").css("overflow","hidden");
};


/** 当选择不同的社区时 进入对应得板块
 * @param   id  社区id
 * */ 
function xuanze(id){
	if(id==6){
		layer.msg("暂未开放，敬请期待！");
		return false;
	}
    //请求社区详情
    $.common.ajaxPost('/user/Community/getCommunityDetail',{
        id:id
    } , function(data) {
        var html = "";
        var temp = $(".difficulty_none").clone();
        $(".difficulty_none").remove();
        var o = data.result;
        var html=temp.html();

        html=html.replace(/\{id\}/g,o.id);
        html=html.replace(/\{multiple\}/g,o.multiple);

        html=html.replace(/\{name\}/g,o.name);
        html=html.replace(/\{low_sum\}/g,o.low_sum);
        html=html.replace(/\{top_sum\}/g,o.top_sum);
        html=html.replace(/\{message\}/g,o.message);
        html=html.replace(/\{guadan_currency\}/g,o.guadan_currency);
        html=html.replace(/\{need_currency\}/g,o.need_currency);
        $(".difficulty").append("<div>"+html+"</div>");
    });

    //隐藏社区列表，显示提供资助模块
    $(".main").hide();
    $(".difficulty").show();
};

var utext , pas=false;
/** js验证 资助金额(本input对象onblue事件)
 * @param   low_sum     最少金额
 * @param   top_sum     最高金额
 * @param   multiple    金额倍数
 * */
function poor_c_utext(low_sum , top_sum , multiple){
	$(".utext").empty();
    var money = ($('#money').val());
    if(money=='' || money == null || money=='undefined') {
        $(".utext-msg").html(low_sum+"-"+top_sum+"必须是"+multiple+"的倍数").css("color","red");
        $(".utext").css("border","1px solid red");
        utext = false;
        return false;
    }
    if(money<low_sum || money>top_sum || money%multiple!=0) {
        $(".utext-msg").html(low_sum+"-"+top_sum+"必须是"+multiple+"的倍数").css("color","red");
        $(".utext").css("border","1px solid red");
        utext = false;
        return false;
    }
    $(".utext-msg").css('color','#3c3c3c');
    $(".utext").css("border","1px solid #ccc");
    utext = true;
    //$(".difficulty_c .utext-msg").html("1000-3000必须是100的倍数").css("color","#3c3c3c");
};

/** js验证 二级密码（本input对象onblur事件）
 * @param   password    二级密码
 * */
function difficulty_c_upwd(){
	$(".upwd").empty();
    var password = $('#password').val();
    if(password=='' || password == null || password=='undefined') {
        $(".upwd-msg").html("忘记密码").css("color","red");
        $(".upwd").css("border","1px solid red");
        pas = false;
        return false;
    }
    $(".upwd-msg").css("color","#3c3c3c");
    $(".upwd").css("border","1px solid #ccc");
    pas = true;
};


/** 提供资助 Ajax
 * */
$._provide_action_flag = true;
function difficulty_click(id,obj){
//	$(obj).attr("disabled",true);
    var money = ($('#money').val());
    var password = $('#password').val();
    var need_currency = parseInt($('#need_currency').html());
    var token = $("#provide_token_id").val();
    
    if(money!='' && password!='') {
        if($._provide_action_flag != true) {
            return false;
        }
        $._provide_action_flag = false;
        $("#provide_action_button").attr("disabled",true);
        $("#provide_action_button").css("background","#cecece");
        $.common.ajaxPost('checkgivehelp',{
            
            amount:money,
            amount_password:password,
            
        },function(data) {
            if(data.errorCode==0){
                layer.msg('发布成功！');
                setTimeout(function() {
                    window.location.href = "";
                } , 1500);
                $._provide_action_flag = true;
                $('.mun1').html(parseInt(($('.mun1').html())) - need_currency);
                //window.location.href="/index/community/CommunityList.html"
            } else if(data.errorCode) {
                if(data.errorCode == 30){
                    if(confirm("二级密码不能与登录密码一致！请确定前往修改二级密码。")){
                        window.location.href = "{:U('User/editPassword')}";
                        return false;
                    }
                }else{
                    layer.msg("发布失败，"+data.errorMsg);
                }
            }
            $._provide_action_flag = true;
//            $(obj).removeAttr("disabled");
            $("#provide_action_button").removeAttr("disabled");
            $("#provide_action_button").css("background","");
            $("#provide_token_id").val(data.token);
        });
    }
};

/** 提供资助 Ajax
 * */
$._provide_action_flag = true;
function difficulty2_click(id,obj){
//	$(obj).attr("disabled",true);
    var money = $('#money').val();
    var password = $('#password').val();
    var b = ($(".sum").text());
    if(money=='' || money=='undefined') {
        layer.msg('请填写受助金额');
        return false;
    }
    if(parseInt(money)%100 != 0) {
        layer.msg('受助金额必须是100的整倍数');
        return false;
    }
    if(parseInt(money) <= 0) {
        layer.msg('请填写受助金额');
        return false;
    }
    if(parseInt(money) > parseInt(b)) {
            layer.msg('钱包余额不足');
            return false;
    }
    if(money!='' && password!='') {
        if($._provide_action_flag != true) {
            return false;
        }
        $._provide_action_flag = false;
        $("#provide_action_button").attr("disabled",true);
        $("#provide_action_button").css("background","#cecece");
        $.common.ajaxPost('checkgethelp',{
            
            amount:money,
            amount_password:password,
            
        },function(data) {
            if(data.errorCode==0){
                layer.msg('发布成功！');
                setTimeout(function() {
                    window.location.href = "";
                } , 1500);
                $._provide_action_flag = true;
                $('.mun1').html(parseInt(($('.mun1').html())) - need_currency);
                //window.location.href="/index/community/CommunityList.html"
            } else if(data.errorCode) {
                if(data.errorCode == 30){
                    if(confirm("二级密码不能与登录密码一致！请确定前往修改二级密码。")){
                        window.location.href = "{:U('User/editPassword')}";
                        return false;
                    }
                }else{
                    layer.msg("发布失败，"+data.errorMsg);
                }
            }
            $._provide_action_flag = true;
//            $(obj).removeAttr("disabled");
            $("#provide_action_button").removeAttr("disabled");
            $("#provide_action_button").css("background","");
            $("#provide_token_id").val(data.token);
        });
    }
};

