/***********************************************\
 * 公告通知、审核公告
 * 
 * **/


    //分页JS
    function callBackPagination(callback,totalCount,limit,showCount) {
    //    var totalCount =  11;//总条数
    //	var showCount = 3;//
    //    var limit =  2;//每分显示条数
        $('#callBackPager').extendPagination({
            totalCount: totalCount,
            showCount: showCount,
            limit: limit,
            callback: function (curr, limit, totalCount) {
                eval(callback+"("+limit+","+curr+",0)");
            }
        });
    }
    
$(function(){
    
    



    $('.tell li').click(function(){
        var flag = $(this).index();
        if(flag == '0'){
            $('.system .message').html('平台公告').css('color','#a90b16');
        }else if(flag == '1'){
			$('.system .message').html('审核通知').css('color','#a90b16');
		}else if(flag == '2'){
			$('.system .message').html('解封通知').css('color','#a90b16');
		}
    });
    $('.tell>li>a').click(function(e){
        e.preventDefault();
        $(this).addClass('active').parent().siblings().children('.active').removeClass('active');
        var id=$(this).attr('href');
        $(id).addClass('actives').siblings('.actives').removeClass('actives');
    });
    
    
    
    
    //调用
    //getNewsList(12 , 1 , 1);
    // 公告通知
    function getNewsList(page_num , page , pagination) {
        $('#callBackPager').hide();
        $.common.ajaxPost('/user/news/getNewsLists' , {
            type:1,
            page_num:page_num,
            page:page
        } , function(data) {
            if(data.result.list=='' || data.result.list==[]) {
                $('.result_null').show();
                return false;
            }
            $('.result_null').hide();
            var html ='';
            $.each(data.result.list,function(i,o){
                html+="<tr>"
                    +"<td>"+"SXH"+o.id+"</td>"
                    +"<td class='title'><span style='cursor:pointer' onclick='newsDetail("+o.id+")'>"+o.title+"</span></td>"
                    +"<td>公告通知</td>"
                    +"<td>"+o.create_time+"</td>"
                    +"</tr>"
            });
            $(".gonggaotongzhi").html(html)
            if(pagination == 1){
                callBackPagination("getNewsList",data.result.total,page_num,page);
            }
            $('#callBackPager').show();
        });
    }
    
    // 审核通知
    function getNewsListPerson(page_num , page , pagination) {
        $('#callBackPager').hide();
        $.common.ajaxPost('/user/news/auitMsgList' , {
            type:2,
            page_num:page_num,
            page:page 
        } , function(data) {
            var html ='';
            if(data.result.list=='' || data.result.list==[]) {
                $('.result_null').show();
                return false;
            }
            $('.result_null').hide();
            $.each(data.result.list,function(i,o){
                html+="<tr>"
                    +"<td>"+"SXH"+o.id+"</td>"
                    +"<td class='title'><span style='cursor:pointer;' onclick='autiDetail("+o.id+")'>"+o.attend+"</span></td>"
                    +"<td>"+o.create_time+"</td>"
                    +"</tr>"
            });
            $(".shenhetongzhi").html(html) 
            if(pagination == 1){
                callBackPagination("getNewsListPerson",data.result.total,page_num,page);
            }
            $('#callBackPager').show();
        });
    }
    
    
    $('#news_list').click(function() {
        getNewsList(12 , 1 , 1);
    });
    $('#news_list_person').click(function() {
        getNewsListPerson(12 , 1 , 1);
    });

    
})


    /** 公告详情
     * */
    function newsDetail(id) {
        $.common.ajaxPost('getNewsDetail' , {
            'id' : id,
        } , function(data) {
            if(data.errorCode == 0) {
                layer.open({
                    type: 1,//Page层类型
                    title:data.result.title,//标题
                    area: ['900px', '600px'],
                    shadeClose: true, //点击遮罩关闭
                    shade: 0.6 ,//遮罩透明度
                    maxmin: false ,//允许全屏最小化
                    anim: -1 ,//0-6的动画形式，-1不开启
                    skin: 'yourclass',
                    //内容
                    content: '\<\div style="padding:20px;">'+data.result.content+'\<\/div>\n\
                            \n\
                            <div style="text-align:right;padding:20px;">【公告通知】&nbsp;&nbsp;&nbsp;&nbsp;'+data.result.create_time+'</div>',
                });
            } else {
                layer.msg(data.errorMsg);
            }
        });
    }

    /** 公告详情
     * */
    function newsIndexDetail(id) {
        $.common.ajaxPost("Home/news/getNewsDetail" , {
            'id' : id,
        } , function(data) {
            if(data.errorCode == 0) {
                layer.open({
                    type: 1,//Page层类型
                    title:data.result.title,//标题
                    area: ['900px', '600px'],
                    shadeClose: true, //点击遮罩关闭
                    shade: 0.6 ,//遮罩透明度
                    maxmin: false ,//允许全屏最小化
                    anim: -1 ,//0-6的动画形式，-1不开启
                    skin: 'yourclass',
                    //内容
                    content: '\<\div style="padding:20px;">'+data.result.content+'\<\/div>\n\
                                \n\
                                <div style="text-align:right;padding:20px;">【公告通知】&nbsp;&nbsp;&nbsp;&nbsp;'+data.result.create_time+'</div>',
                });
            } else {
                layer.msg(data.errorMsg);
            }
        });
    }
    
    
    function autiDetail(id) {
        $.common.ajaxPost('/user/news/getAutiNewsDetail' , {
            'id' : id,
        } , function(data) {
            if(data.errorCode == 0) {
                layer.open({
                    type: 1,//Page层类型
                    title:'审核通知',//标题
                    area: ['900px', '600px'],
                    shadeClose: true, //点击遮罩关闭
                    shade: 0.6 ,//遮罩透明度
                    maxmin: true ,//允许全屏最小化
                    anim: -1 ,//0-6的动画形式，-1不开启
                    skin: 'yourclass',
                    //内容
                    content: '\<\div style="padding:20px;">'+data.result.content+'\<\/div>\n\
                            \n\
                            <div style="text-align:right;padding:20px;">【审核通知】&nbsp;&nbsp;&nbsp;&nbsp;'+data.result.create_time+'</div>',
                });
            } else {
                layer.msg(data.errorMsg);
            }
        });
    }
    
    
    
    // 解封通知
    function getDeblockingList(page_num , page , pagination) {
        $('#callBackPager').hide();
        $.common.ajaxPost('/user/Deblocking/applyDeblockingList' , {
            page_num:page_num,
            page:page 
        } , function(data) {
            var html ='';
            if(data.result.list=='' || data.result.list==[]) {
                $('.result_null').show();
                return false;
            }
            $('.result_null').hide();
            $.each(data.result.list,function(i,o){
                html+="<tr>"
                    +"<td>"+"SXH"+o.id+"</td>"
                    +"<td class='title'><span style='cursor:pointer;' onclick='getDeblockingDetail("+o.id+")'>"+o.unblock_comtent+"</span></td>"
                    +"<td>"+o.create_time+"</td>"
                    +"</tr>"
            });
            $(".jiefengtongzhi").html(html);
            $("#t3").show();
            if(pagination == 1){
                callBackPagination("getNewsListPerson",data.result.total,page_num,page);
            }
            $('#callBackPager').show();
        });
    }
    //解封通知详情
    function getDeblockingDetail(id) {
        $.common.ajaxPost('/user/Deblocking/applyDeblockingDetail' , {
            'id' : id,
        } , function(data) {
            if(data.errorCode == 0) {
                layer.open({
                    type: 1,//Page层类型
                    title:'解封通知',//标题
                    area: ['900px', '600px'],
                    shadeClose: true, //点击遮罩关闭
                    shade: 0.6 ,//遮罩透明度
                    maxmin: true ,//允许全屏最小化
                    anim: -1 ,//0-6的动画形式，-1不开启
                    skin: 'yourclass',
                    //内容
                    content: '\<\div style="padding:20px;">'+data.result.unblock_comtent+'\<\/div>\n\
                            \n\
                            <div style="text-align:right;padding:20px;">【解封通知】&nbsp;&nbsp;&nbsp;&nbsp;'+data.result.create_time+'</div>',
                });
            } else {
                layer.msg(data.errorMsg);
            }
        });
    }