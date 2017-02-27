/***************************** 
 * JS公共函数，需先加载layer.js库
 ****************************/

$.common = {
    /** 获取 token
     * */
    getToken:function(){
//    	form_token = $("meta[name=form-token]").attr("content");
//    	return  form_token;
//    	var $token ="";
    	$.ajax({
            url:"/user/index/getToken",
            async:false,
            success:function($res){
                if($res.errorCode == 0){
                    $token= $res.result.token;
                }else{
                    layer.msg('连接失败');
                }
            }
    	   
    	});
    	return $token;
    },
    /** Ajax 请求
     * */
    ajaxPost:function (url,params,backFun){
            load = layer.load();
            $.ajax({
                url:url,
                type:"POST",
                data:params,
                // beforeSend: function(req) {
                //     req.setRequestHeader('X-CSRF-Token',$.common.getToken())
                // },
                success:function($data){
                    if($data.errorCode == -21){
                        layer.msg('登录已超时，请重新登录');
                        setTimeout(function() {
                           window.location.href="/index/index";
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
    
    tipsShow : function(value , obj) {
        layer.tips(value , obj);
    },
    
    
    
    /** <form表单提交+上传文件>
     * */
    ajaxFormPost : function(form , url , params , callback) {
        load = layer.load();
        $(form).ajaxSubmit({
            url:url,
            type : 'post',
            data : params,
            enctype : 'multipart/form-data',
            // beforeSend: function(req) {
            //     req.setRequestHeader('X-CSRF-Token',$.common.getToken())
            // },
            success : function(data) {
                if(data.errorCode == -21) {
                    layer.close(load);
                    layer.msg('登录已超时');
                    setTimeout(function() {
                        window.location.href = '/index/index/index.html';
                    } , 1500);
                }
                layer.close(load);
                callback(data);
            },
            error:function(){
                layer.close(load);
            }
        });
    }
    
    
    
}


/** 获取 token
 * */
function getToken(){
    var $token ="";
    $.ajax({
        url:"/company/index/getToken",
        async:false,
        success:function($res){
            if($res.errorCode == 0){
                $token= $res.result.token;
            }else{
                layer.msg('连接失败');
            }
        }

    });
    return $token;
}
/** Ajax 请求
 * */
function ajaxPost(url,params,backFun){
        load = layer.load();
        $.ajax({
            url:url,
            type:"POST",
            data:params,
            // beforeSend: function(req) {
            //     req.setRequestHeader('X-CSRF-Token',getToken())
            // },
            success:function($data){
                if($data.errorCode == -21){
                    layer.msg('登录已超时，请重新登录');
                    setTimeout(function() {
                       window.location.href="/index/index";
                    } , 1500);
                }
                backFun($data);
                layer.close(load);
            },
            error:function(){
                layer.close(load);
            }
        });
}











/** 该功能即将开发，敬请期待！
 * */
function isNotOpen(msg) {
    if(msg=='' || msg==null || msg=='undefined') {
        msg = '该功能即将开发，敬请期待！';
    }
    layer.msg(msg);
}



/** 停留几毫秒之后 跳转页面
 * @param   msg     弹出框信息
 * @param   url     跳转页面
 * @param   time    停留时间
 * @author  江雄杰
 * @time    2016-10-12
 * */
function toLocationUrl(msg , url , time) {
    layer.msg(msg);
    setTimeout(function() {
        window.location.href = url;
    },time);
}


/** 计算剩余时间
 * @param   second  剩余秒数
 */
function returnResidualTime(second , callback) {
    day = Math.floor(second/(3600*24));
    second = second%(3600*24);//除去整天之后剩余的时间
    hour = Math.floor(second/3600);
    second = second%3600;//除去整小时之后剩余的时间 
    minute = Math.floor(second/60);
    second = second%60;//除去整分钟之后剩余的时间 
    //返回字符串

    if(minute < 10) {
        minute = '0'+minute;
    }
    if(second < 10) {
        second = '0'+second;
    }
    //return $day.'天'.$hour.'小时'.$minute.'分'.$second.'秒';
    res = (day*24+hour)+'小时'+(minute)+'分'+(second)+'秒';
    callback(res);
}



/**              
* 时间戳转换日期
* @param <int> unixTime    待时间戳(秒)              
* @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)              
* @param <int>  timeZone   时区              
*/
function unixToDate(unixTime, isFull, timeZone) {
    if (typeof (timeZone) == 'number')
    {
        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
    }
    var time = new Date(unixTime * 1000);
    var ymdhis = "";
    ymdhis += time.getUTCFullYear() + "-";
    ymdhis += (time.getUTCMonth()+1) + "-";
    ymdhis += time.getUTCDate();
    if (isFull === true)
    {
        ymdhis += " " + time.getUTCHours() + ":";
        ymdhis += time.getUTCMinutes() + ":";
        ymdhis += time.getUTCSeconds();
    }
    return ymdhis;
}
/**              
* 日期 转换为 Unix时间戳
* @param <string> 2014-01-01 20:20:20  日期格式              
* @return <int>        unix时间戳(秒)              
*/
function dateToUnix(string) {
   var f = string.split(' ', 2);
   var d = (f[0] ? f[0] : '').split('-', 3);
   var t = (f[1] ? f[1] : '').split(':', 3);
   return (new Date(
           parseInt(d[0], 10) || null,
           (parseInt(d[1], 10) || 1) - 1,
           parseInt(d[2], 10) || null,
           parseInt(t[0], 10) || null,
           parseInt(t[1], 10) || null,
           parseInt(t[2], 10) || null
           )).getTime() / 1000;
}