/********************************
 * 个人中心 - 完善资料
 * @returns {undefined}
 */

$(function () {
    var mz = /^[^&^=^%^$^@^\)^\)^\~^\+^\[^\]^\}^\{^\<^\>^\*^\d]{2,80}$/i;
    var tel = /^[1][3-8]\d{9}$/
    var email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,12})+$/
   // var id = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/
    var id=/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
    var shenfenz =/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X|x)$/; 
    var flag,flag2,flag3,flag4,flag5,flag6,flag7,flag8,flag9,flag10 = false;

    // 姓名
    $('input[type=user]').blur(function () {
        flag = false;
        if ($('input[type=user]').val() == "") {
            $('.b_01').show()
        }
        else if (mz.test($('input[type=user]').val())) {
            $('input[type=user]').val()
            flag = true;
        } else {
            $('.b_02').show()
        }
    });
    $('input[type=user]').focus(function () {
        $('.b_01').hide(),
            $('.b_02').hide()
    });
    // 电话
    $('input[type=tel]').blur(function () {
        if ($('input[type=tel]').val() == "") {
            $(".b_03").show()
        } else if (tel.test($('input[type=tel]').val())) {
            $('input[type=tel]').val()
            flag2 = true;
        } else {
            $('.b_04').show()
        }
    });
    $('input[type=tel]').focus(function () {
        $('.b_03').hide(),
            $('.b_04').hide()
    });
    // 邮箱
    $('input[type=email]').blur(function () {
        if ($('input[type=email]').val() == "") {
            $(".b_05").show()
        } else if (email.test($('input[type=email]').val())) {
            $('input[type=email]').val()
            flag3 = true;
        } else {
            $('.b_06').show()
        }

    });
    $('input[type=email]').focus(function () {
        $('.b_05').hide(),
            $('.b_06').hide()
    });
    // 身份证号码
    var sfid = 1;
    $('#passport_selected').change(function(){
        sfid =$(this).val()
        $(".b_07").hide()
        $(".b_08").hide()
        $('input[type=id]').val("")
    })
    $('input[type=id]').blur(function () {
        
        if ($('input[type=id]').val() == "") {
            $(".b_07").show()
        } else if (shenfenz.test($('input[type=id]').val()) ) {//
            $('input[type=id]').val()
            flag4 = true;
        } else {
            $('.b_08').html("身份证书写不规范").show()
        }
        

    });
    $('input[type=id]').focus(function () {
        $('.b_07').hide(),
            $('.b_08').hide()
    });
    // 收货地址
    $('input[type=address]').blur(function () {
        if ($('input[type=address]').val() == "") {
            $(".b_09").show()
        }else{
            flag5 = true;
        }
    });
    $('input[type=address]').focus(function () {
        $('.b_09').hide()

    });
    // 支付宝 （选填）
    $('input[type=zfb]').blur(function () {
    	//flag6 = true;
        if ($('input[type=zfb]').val() == "") {
            $(".b_10").show()
        }else{
            flag6 = true;
        }
    });
    $('input[type=zfb]').focus(function () {
        $('.b_10').hide()

    });
    // 银行卡
    var yhangk = /\d{5,30}/;
    $('input[type=yh]').blur(function () {
        if ($('input[type=yh]').val() == "") {
            $(".b_12").html('银行卡不能为空').show()
        }else if(yhangk.test($('input[type=yh]').val())){
            flag7 = true;
        }else{
            $(".b_12").html('银行卡格式有误').show()
        }
    });
    $('input[type=yh]').focus(function () {
        $('.b_12').hide()

    });
    // 开户行账户
    $('input[type=kf]').blur(function () {
        if ($('input[type=kf]').val() == "") {
            $(".b_14").show()
        }else{
            flag8 = true;
        }
    });
    $('input[type=kf]').focus(function () {
        $('.b_14').hide()

    });

    // 所在支行
    $('input[type=zh]').blur(function () {
        if ($('input[type=zh]').val() == "") {
            $(".b_16").show()
        }else{
            flag9 = true;
        }
    });
    $('input[type=zh]').focus(function () {
        $('.b_16').hide()

    });
    
    
    
    //点击身份验证
    /** 获取用户 个人信息 Start
     * **/
//    $.common.ajaxPost('/user/user/getUserInfoInforMation' , {
//        
//    } , function(data) {
//            if(data.errorCode == 0) {
//                
//                if(data.result.image_a != '') {
//                    $('#image_a').css('background-image' , 'url('+data.result.image_a+')');
//                }
//                if(data.result.image_b != '') {
//                    $('#image_b').css('background-image' ,  'url('+data.result.image_b+')');
//                }
//                if(data.result.image_c != '') {
//                    $('#image_c').css('background-image' ,  'url('+data.result.image_c+')');
//                }
//                //如果审核已通过
//                if(data.result.verify == 2) {
//                    $('#image_a form,#image_a span   , #image_b form,#image_b span  ,  #image_c form,#image_c span').remove();
//                    
//                    $('#verify_title_1,#verify_title_2,#verify_title_3').html('审核已通过，不能修改资料信息');
//                    $('#info_name,#info_phone,#info_email,#info_card_id,#info_alipay_account,#pro,#city,#info_address,#info_bank_account,#info_bank_name')
//                            .attr('disabled','true');
//                    $('#pro').attr('selected','selected');
//                    $('#city').attr('selected','selected');
//                    
//                    if(data.result.image_a != '') {
//                        $('#image_a').wrap('<a href="'+data.result.image_a+'" target="_blank"></a>');
//                    }
//                    if(data.result.image_b != '') {
//                        $('#image_b').wrap('<a href="'+data.result.image_b+'" target="_blank"></a>');
//                    }
//                    if(data.result.image_c != '') {
//                        $('#image_c').wrap('<a href="'+data.result.image_c+'" target="_blank"></a>');
//                    }
//                    $('.yanzheng .syb').hide();
//                    $('.yanzheng #subimt_info').hide();
//                    var complate = '<map name="Map" id="Map">\n\
//                                        <area onclick="clickInfo();" shape="rect" coords="1,1,341,55" href="javascript:void(0);" />\n\
//                                        <area onclick="clickInfoImage();" shape="rect" coords="374,2,715,55" href="javascript:void(0);" />\n\
//                                        <area onclick="clickShenhe();" shape="rect" coords="750,1,1091,56" href="javascript:void(0);" />\n\
//                                    </map>';
//                    $('#complate_image').append(complate);
//                    $('.shenhe h2').html('您已通过审核');
//                    $('.shenhe section').remove();
//                    
//                    $('#next1').hide();
//                }
//                    
//                if(data.result.name != '') {
//                    $('#info_name').val(data.result.name);
//                    flag = true;
//                }
//                if(data.result.phone != '') {
//                    $('#info_phone').val(data.result.phone);
//                    flag2 = true;
//                }
//                if(data.result.email != '') {
//                    $('#info_email').val(data.result.email);
//                    flag3 = true;
//                }
//                if(data.result.card_type == 1) {
//                    $('#info_card_id').val(data.result.card_id);
//                    flag4 = true;
//                } else {
//                    $('#passport_selected').val(2);
//                    $('#info_card_id').val(data.result.passport);
//                    flag4 = true;
//                }
//                if(data.result.alipay_account != '') {
//                    $('#info_alipay_account').val(data.result.alipay_account);
//                    flag6 = true;
//                }
//                
//                if(data.result.province != '' && data.result.city != '') {
//                     $.initProv("#pro", "#city", data.result.province,data.result.city);
////                    $('#pro').children('option[selected="selected"]').html(data.result.province);//省
//                    flag_province = true;
//                    flag_city = true;
//                } else {
//                    $('#pro').val('-1');
//                    $('#city').val('-1');
//                }
//                
//                if(data.result.address != '') {
//                    $('#info_address').val(data.result.address);//收货地址
//                    flag5 = true;
//                }
//                if(data.result.bank_account != '') {
//                    $('#info_bank_account').val(data.result.bank_account);//银行帐号
//                    flag7 = true;
//                }
//                if(data.result.bank_name != '') {
//                    $('#info_bank_name').val(data.result.bank_name);//银行
//                    flag8 = true;
//                }
////                $('#info_branch_bank').val(data.result.name);//支行
//            } else {
//                
//            }
//    });
    //获取用户 个人信息 End
    

    $._user_info_flag = true;
    // 点击下一步个人信息
    $('#next1').click(function () {
       
//            alert(flag);//姓名
//            alert(flag2);//电话
//            alert(flag3);//邮箱
//            alert(flag4);//身份证号码
//            alert(flag5);//收货地址
//            alert(flag6);//支付宝
//            alert(flag7);//银行卡
//            alert(flag8);//开户行
//            alert(flag9);//所在支行
         
           
        
        if($("#info_name").val() == '') {
            layer.msg('姓名不能为空');
            flag = false;
        }else{
            flag = true;
        }
        if($("#info_phone").val() == '') {
            layer.msg('手机号不能为空');
            flag2 = false;
        }else{
            flag2 = true;
        }
        if($('input[name=idcard]').val() == '') {
            layer.msg('身份证号不能为空');
            flag3 = false;
        }else{
            flag3 = true;
        }
        if($('input[name=alipay]').val() == '') {
            layer.msg('支付宝账号不能为空');
            flag4 = false;
        }else{
            flag4 = true;
        }
        if($('input[name=wechat]').val() == '') {
            layer.msg('微信号不能为空');
            flag5 = false;
        }else{
            flag5 = true;
        }
        if($('input[name=bank]').val() == '') {
            layer.msg('银行不能为空');
            flag6 = false;
        }else{
            flag6 = true;
        }
        if($('input[name=bank_card]').val() == '') {
            layer.msg('银行卡号不能为空');
            flag7 = false;
        }else{
            flag7 = true;
        }


        if( flag && flag2 && flag3 && flag4 && flag5 && flag6 && flag7  ){
            
            $.common.ajaxPost('userinfo' , {
                name:$('input[name=name]').val(),  //姓名
                phone:$('input[name=phone]').val(),  //电话      
                idcard:$('input[name=idcard]').val(),  //身份证号码或者护照号码              
                alipay:$('[name=alipay]').val(),  //支付宝
                wechat:$('[name=wechat]').val(),  //支付宝
                bank:$('input[name=bank]').val(),  //开户行
                bank_card:$('input[name=bank_card]').val(), //银行卡号
                
            } , function(data) {
                if(data.errorCode==0){
//                    $(".xinxi,.shenhe").hide();
//                    $(".yanzheng").show();
                    $('.xinxi,.shenhe').hide();
                    $('.yanzheng').show();
                } else {
                    layer.msg(data.errorMsg);
                    return false;
                }
            });
            
        } else {
           layer.msg('请填写完整信息');
//            $._user_info_flag = false;
        }
        
    });
    
    $('#subimt_info').click(function() {
//    $('.user_info_next_submit').click(function() {
        
        var xxx = layer.load();
//        setTimeout(function() {
//            layer.close(xxx);
//            $(".xinxi,.yanzheng").hide();
//            $(".shenhe").show();
//        } , 500);
        $.common.ajaxPost('updateImg' , {
                idcard_imga:$('input[name=idcard_imga]').val(),  //姓名
                idcard_imgb:$('input[name=idcard_imgb]').val(),  //电话      
                
                
            } , function(data) {
                if(data.errorCode==0){
                    layer.close(xxx);
                    $(".xinxi,.yanzheng").hide();
                     $(".shenhe").show();
                } else {
                    layer.msg(data.errorMsg);
                    return false;
                }
            });
        if($._user_info_flag == false) {
            return false;
        }
//        
//        var  pro = $("#pro").val();
//        a = $('#pro').children('option[value='+pro+']').html(); //获取省
//        var city = $("#city").val();  
//        b = $('#city').children('option[value='+city+']').html();  //获取市
//        $.common.ajaxPost('/user/user/improveUserInfo' , {
//            name:$('input[type=user]').val(),  //姓名
//            phone:$('input[type=tel]').val(),  //电话
//            email:$('input[type=email]').val(), //邮箱
//            card_id:$('input[type=id]').val(),  //身份证号码或者护照号码
//			card_type:$(".id").val(),//判断是身份证还是护照
//            alipay_account:$('input[type=zfb]').val(),  //支付宝
//            bank_name:$('input[type=kf]').val(),  //开户行
//            bank_account:$('input[type=yh]').val(), //银行卡号
//            province:a,  //省
//            city:b, // 市
//            address:$('input[type=address]').val(), //收货地址
//            weixin_account:""  //微信号  暂时为空
//        } , function(data) {
//            if(data.errorCode==0){
//                $(".xinxi,.yanzheng").hide();
//                $(".shenhe").show();
//            } else {
//                layer.msg(data.errorMsg);
//            }
//        });
    });


    $('.syb').click(function () {
        $('.xinxi').show();
        $('.yanzheng').hide()
    })

        $(".sc1").click(function(){
            $(".sl1").click();
        })
 
//    $('.tijiao').click(function () {   
//        if($(".sl1").val()!=""&&$(".sl2").val()!=""&&$(".sl3").val()!=""){
//            var img_a = $(".sl1").val();
//            var img_b = $(".sl2").val();
//            var img_c = $(".sl3").val();
//
//        }
//    })
    
    
    
    
})//$(function)   End




    //点击个人信息
    function clickInfo() {
        $('.yanzheng').hide();
        $('.shenhe').hide();
        $('#info_form').show();
    }
    function clickInfoImage() {
        $('#info_form').hide();
        $('.shenhe').hide();
        $('.yanzheng').show();
    }
    function clickShenhe() {
        $('#info_form').hide();
        $('.yanzheng').hide();
        $('.shenhe').show();
    }