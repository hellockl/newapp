$.Main = {
    
    ajaxPost:function (url,params,backFun){
            load = layer.load();
            $.ajax({
                    url:url,
                    type:"POST",
                    data:params,
                    success:function($data){
                            if($data.errorCode == -21){
                                layer.msg('登录已超时，请重新登录');
                                setTimeout(function() {
                                    window.location.href="/index//index/index.html";
                                } , 1500);
                            }

                            backFun($data);
                            layer.close(load);
                    },
                    error:function(){
                            layer.close(load);
                    }
            });
    },
    
    
    /** ajax注册
     * @person  个人注册|企业注册
     * @param   company     值：person 或 company （个人注册或企业注册）
     * @author  江雄杰
     * @time    2016-10-10
     * */
    register : function(company) {
        switch(company) {
            /*** 个人注册 ****/
            case 'person':
                //加载框
                var username        = $('#username').val();
                var password        = $('#password').val();
                var repassword      = $('#repassword').val();
                var name            = $('#name').val();
                var phone           = $('#phone').val();
                var referee_name    = $('#referee_name').val();
                
                $.common.ajaxPost('/user/index/register' , {
                    company : company,
                    username : username,
                    password : password,
                    repassword : repassword,
                    name : name,
                    phone : phone,
                    referee_name : referee_name
                } , function(data) {
                    if(data.errorCode == 0) {
                        layer.msg('注册成功！'+"即将为你跳转到登录页面...");
                        setTimeout(function() {
                            location.href= '/login.html';
                        },2000);
                    } else {
                        //alert(data.errorMsg);
                    }
                });
                break;
                
        }
    },
    
    /** ajax登录（不分个人 或企业）
     * @author  江雄杰
     * @time    2016-10-10
     * */
    login : function() {
        //a.toUpperCase()转大写
        var username = $('#username').val();
        var password = $('#password').val();
        if(username=='' || password=='') {
            layer.msg('帐户名或密码不能为空');
            return false;
        }
        
        //声明 是否记住密码
        var remember_pass = $('.remember_password').attr('value');
        
        $.common.ajaxPost('/user/index/login' , {
            username : username,
            password : password,
        } , function(data) {
            if(data.errorCode == 408) {
                layer.msg(data.errorMsg);
                return false;
            }
            if(data.errorCode == 0) {
                //登录成功，再记住帐号与密码
                if(remember_pass == 1){
                    $.cookie("username",username, { expires: 3 });
                    $.cookie("password",password, { expires: 3 });
                } else {
                    //不记住密码
                    $.cookie('username' , '');
                    $.cookie('password' ,  '');
                }
                toLocationUrl('登录成功，即将为你跳转...' , '/index/user/ydy' , 1000);
            } else {
                layer.msg(data.errorMsg);
            }
        });
    },
     
    
    /** 检测用户名是否已被注册过
     * @param   username    要检测的用户名
     * @return  
     * @author  江雄杰
     * @time    2016-10-11
     * */
    checkUsername : function(username , callback) {
        //alert(username);
        if(username=='' || username=='undefined') {
            alert('error');
        }
      
        $.ajax({
            type : 'post',
            url : 'checkUserName',
            data : {
                user_name : username,
            },
            success : function(data) {
                //errorCode==0 用户已存在
                callback(data);
//                if(data.errorCode==0) {
//                    callback(data);
//                } else {
//                    return  false;
//                }
            },
            error : function() {
                return false;
            }
        });
    },
    
    
    /** 检测手机号是否已被注册过
     * @param   phone    要检测的手机号
     * @return  
     * @author  江雄杰
     * @time    2016-10-11
     * */
    checkPhone : function(phone , callback) {
        if(phone=='' || phone=='undefined') {
            alert('error');
        }
        $.ajax({
            type : 'post',
            url : 'checkPhone',
            data : {
                phone :phone,
            },
            success : function(data) {
                callback(data);
            },
            error : function() {
                return false;
            }
        });
    },    
    
    
    
    /** 查用户account钱包余额
     * */
    userAccount : function(callback) {
        $.ajax({
            type:'post',
            url:'/user/user/meritWallet',
            data:{
                
            },
            success : function(data) {
                if(data.errorCode == 0) {
                    callback(data.result);
                } else {
                    
                }
            }
        });
    },
    
    
    
    
    /** 企业注册
     * */
    companyLogin : function() {
        //a.toUpperCase()转大写
        var ii = layer.load();
        var username = $('#company_username').val();
        var password = $('#company_password').val();
        if(username=='' || password=='') {
            layer.close(ii);
            layer.msg('帐户名或密码不能为空');
            return false;
        }
        
        //声明 是否记住密码
        var remember_pass = $('.company_remember_password').attr('value');
        
        $.ajax({
            type : 'post',
            url : '/company/index/login',
            data : {
                username : username,
                password : password,
            },
            success:function (data) {
                if(data.errorCode == 0) {
                    //登录成功，再记住帐号与密码
                    if(remember_pass == 1){
                        $.cookie("company_username",username, { expires: 3 });
                        $.cookie("company_password",password, { expires: 3 });
                    } else {
                        //不记住密码
                        $.cookie('company_username' , '');
                        $.cookie('company_password' ,  '');
                    }
                    toLocationUrl('登录成功，即将为你跳转...' , '/company_index/user/ydyqiye' , 1000);

                } else {
                    layer.msg(data.errorMsg);
                    //alert(data.errorMsg);
                }
                layer.close(ii);
            },
            error:function () {
                layer.close(ii);
                layer.msg("请求错误！");
            }
        });
    },
    
    
    /** 匹配详情 -> 确认收款
     * @param   id      匹配详情表的id
     */
    confirmReceipt : function(id) {
        $.ajax({
            url : '/user/community/confirmPayAction',
            type : 'post',
            data : {
                actid : id,
            },
            success : function(data) {
                if(data.errorCode == 0) {
                    layer.msg('确认收款成功！');
                } else if(data.errorCode == 30){
                    if(confirm("二级密码不能与登录密码一致！请确定前往修改二级密码。")){
                        window.location.href = '/index/user/modifyPassword.html';
                        return false;
                    }
                }else{
                    layer.msg(data.errorMsg);
                }
            },
            error : function() {
                layer.msg('确认收款失败！');
            }
        });
    },
    
    
    /** 个人 修改手机号码
     * */
    updatePhone : function() {
        new_phone = '';
        password = '';
        verify = '';
        
        $.ajax({
            url : '/user/user/updatePhone',
            type : 'post',
            data : {
                new_phone : new_phone,//新手机号码
                password : password,//二级密码
                verify : verify,//验证码
            },
            success : function(data) {
                if(data.errorCode) {
                    layer.msg('修改成功');
                } else {
                    layer.msg(error.Msg);
                }
            },
            error : function() {
                layer.msg('连接失败');
            }
        });
    }
}