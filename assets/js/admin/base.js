$(document).ready(function () {
    layout();
    checkURL();
    $(document).on('click', 'a.ajax-href[href!="#"]', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        var url = $this.attr('href');
        var title = $this.text();
        var openType=$this.attr('openType');

        $('.content-header>h1').html(title);
        var target = $this.attr('target')?$this.attr('target'):'.page-content';
                if (window.location.search) {
                    window.location.href =
                        window.location.href.replace(window.location.search, '')
                            .replace(window.location.hash, '') + '#' +url;
                } else {
                    window.location.hash = url;
                }
        loadURL(url,target,openType);
    });
});
$(window).resize(function(){
    layout();
});

function layout() {
    var height = $(window).height();
    var left_width = $('.main-sidebar').width();
    //var right_width = $(window).width() - left_width-5;
    // $(".main-content").width(right_width);
    $(".main-content").height(height);
   $(".page-content").height(height-50);//浏览器当前窗口可视区域高度
    $('.main-sidebar').height(height);

}
// CHECK TO SEE IF URL EXISTS
function checkURL() {
    //get the url by removing the hash
    var url = window.location.hash.replace(/^#/, '');
    var container = $('.page-content');
    if (url) {
        var that = $('.sidebar-menu a[href="' + url + '"]');
        that.parent().addClass('active');
        $('.content-header>h1').html(that.text());
    }else{
        var url = $('.sidebar-menu').find('a').eq(0).attr('href');
        $('.sidebar-menu').find('li').eq(0).addClass('active');
    }
    loadURL(url, container);
}

// LOAD AJAX PAGES
function loadURL(url, container,openType) {
    var url = arguments[0] ? arguments[0] :'';
    var container = arguments[1] ? arguments[1] : '';
    var openType = arguments[2] ? arguments[2] : '';

    var container = ''!= container?$(container):$('.page-content');
    if(openType=='frame'){
        container.html('<iframe src="'+url+'" width="100%" height="100%" frameborder="0"></iframe>');
    }else{
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'html',
            cache: true, // (warning: this will cause a timestamp and will call the request twice)
            beforeSend: function () {
                // cog placed
                container.html('<p class="loading"><i class="fa fa-spinner fa-spin"></i> 加载中...</p>');
            },
            success: function (data) {
                container.css({
                    opacity: '0.0'
                }).html(data).delay(50).animate({
                    opacity: '1.0'
                }, 300);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                container.html('<h4 style="margin-top:10px; display:block; text-align:left"><i class="fa fa-warning txt-color-orangeDark"></i> Error 404! 访问的页面不存在.</h4>');
            }
        });
    }
}
function pageLoad(url,container){
    loadURL(url,container);
}

// Ajax 分页
$(document).on('click', '.page-bar a[href!="#"]', function (e) {
    e.preventDefault();
    var $this = $(e.currentTarget);
    var url = $this.attr('href');
    var table_container = $(this).parents('table').parent();
    table_container.html('<p class="loading"><i class="fa fa-spinner fa-spin"></i> 加载中...</p>');
    $.get(url,function(html){
        var table = $(html).find('table').parent().html();
        if(undefined === table)var table = html;
        table_container.html(table);
    });
});
//ajax分页 输入跳转页
$(document).on('click', '.page-bar .goPage', function (e) {
    e.preventDefault();
    var $this = $(e.currentTarget);
    var p =  $this.parents(".input-group").find('input').val();
    var max_page = $this.parents(".input-group").prev().text();
    if(is_numeric(p) && p <= parseInt(max_page) && p>0){
        var url = $this.attr('rel')+p;
        var table_container = $(this).parents('table').parent();
        table_container.html('<p class="loading"><i class="fa fa-spinner fa-spin"></i> 加载中...</p>');
        $.get(url,function(html){
            var table = $(html).find('table').parent().html();
            if(undefined === table)var table = html;
            table_container.html(table);
        });
    }
});

//提示插件
$(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
});
//成功提示绑定
var showSuccess = function(m,title){
    toastr.success(m,title);
}
//信息提示绑定
var showInfo = function(m,title) {
    toastr.info(m,title);
}
//警告提示绑定
var showWarning = function(m,title) {
    toastr.warning(m,title);
}
//错误提示绑定
var showError = function(m,title) {
    toastr.error(m,title);
}
//清除窗口绑定
var clearToastr = function() {
    toastr.clear();
}
/*
 * 弹出对话框，并加载远程内容；
 * @param string title //对话框 标题
 * @param string url //远程URL
 * @param bool isLock //是否背景遮罩
 * @param string follow //元素跟随 ID
 * @returns void
 */
function openAjaxDialog(title, url, isLock, follow) {
    var lock = isLock ? true : false;
    $.get(url, function (data) {
        if (follow) {
            if (document.getElementById(follow)) {
                $.dialog({id: "dlg_" + follow, lock: lock}).title(title).content(data).follow(document.getElementById(follow));
            } else {
                console.log("跟随的元素ID不存在！");
                //showError("跟随的元素ID不存在！");
            }
        } else {
            $.dialog({id: "dlg", lock: lock}).title(title).content(data);
        }
    });
}
/*
 * 弹出对话框；
 * @param string title //对话框标题
 * @param string content //显示内容
 * @param bool isLock //是否背景遮罩
 * @param string follow //元素跟随 ID
 * @returns void
 */
function openDialog(title, content, isLock, follow) {
    var lock = isLock ? true : false;

    if (follow) {
        if (document.getElementById(follow)) {
            $.dialog({id: "dlg_" + follow, lock: lock}).title(title).content(content).follow(document.getElementById(follow));
        } else {
            showError('跟随的元素ID不存在！');
        }
    } else {
        $.dialog({id: "dlg", lock: lock}).title(title).content(content);
    }

}
/*
 * 弹出错误提示框
 * @param string content
 * @param init time
 * @returns void
 */
function showError(content, time, isLock) {
    var t = time ? time : 5000;
    $.dialog({id: "Alert", title: "错误:(", time: t, lock: isLock}).content("<i class='fa fa-times-circle fa-3x' style='color:#d2322d'></i> " + content);
}
/*
 * 弹出错误提示框
 * @param string content
 * @param init time
 * @returns void
 */
function showWarning(content, time, isLock) {
    var t = time ? time : 2000;
    $.dialog({id: "Alert", title: "提示", time: t, lock: isLock}).content("<i class='fa fa-info fa-3x' style='color:#f0ad4e'></i> " + content);
}
/*
 * 弹出成功提示框
 * @param string content
 * @param init time
 * @returns void
 */
function showSuccess(content, time, isLock) {
    var t = time ? time : 2000;
    $.dialog({id: "Alert", title: "OK:)", time: t, lock: isLock}).content("<i class='fa fa-check-square-o fa-3x' style='color:#3c763d'></i> " + content);
}
/*
 * 关闭弹出窗口
 */
function closeDlg(id) {
    var dlg = id ? 'dlg_' + id : 'dlg';
    $.dialog({id: dlg}).close();
}
/*
 * 关闭滑出窗口
 */
function closeSlider(id) {
    $("#" + id).animate({left: '-1999px'})
}

$(document).on('click', 'a.ajax-dialog[href!="#"]', function (e) {
    e.preventDefault();
    var $this = $(e.currentTarget);
    var url = $this.attr('href');
    var title = undefined!==$this.attr('title')?$this.attr('title'):'';
   openAjaxDialog(title,url,1);
});

// 修改指定表的指定字段值
function changeTableVal(table,id_name,id_value,field,obj) {
    var fa = "fa fa-check text-success";
    var value = 1;
    if ($(obj).hasClass('fa-check')) {
        fa = 'fa fa-times text-danger';
        value = 0;
    }
    var url =  "/index.php?m=Admin&c=Index&a=changeTableVal&table=" + table + "&id_name=" + id_name + "&id_value=" + id_value + "&field=" + field + '&value=' + value;
    $.get(url,function(data){
        if('OK' === data) {
            $(obj).attr('class', fa);
            showSuccess('更新成功！');
        }else {
            show('更新失败！');
        }
    });
}

// 修改指定表的排序字段
function updateSort(table,id_name,id_value,field,obj)
{
    var value = $(obj).val();
    var url = "/index.php?m=Admin&c=Index&a=changeTableVal&table="+table+"&id_name="+id_name+"&id_value="+id_value+"&field="+field+'&value='+value;
    $.get(url,function(data){
            'OK' === data?showSuccess('更新成功'):showError('更新失败！');
        });
}
//时间区间选择器
function datePicker(obj,type){
    $(obj).daterangepicker({
        format:"YYYY-MM-DD",
        singleDatePicker: type,
        showDropdowns: true,
        locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
    });
}
/**
 * 模拟PHP函数
 * @param num
 * @returns {string}
 */
function formatCurrency(num) {
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' +
            num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num + '.' + cents);
}
/**
 * 模拟PHP函数
 * @param str
 * @param charlist
 * @returns {string}
 */
function trim(str, charlist) {
    var whitespace, l = 0,
        i = 0;
    str += '';

    if (!charlist) {
        // default list
        whitespace =
            ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
    } else {
        // preg_quote custom list
        charlist += '';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}
/**
 * 模拟PHP函数
 * @param str
 * @param charlist
 * @returns {string}
 */
function ltrim(str, charlist) {
    charlist = !charlist ? ' \\s\u00A0' : (charlist + '')
        .replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    var re = new RegExp('^[' + charlist + ']+', 'g');
    return (str + '')
        .replace(re, '');
}
/**
 * 模拟PHP函数
 * @param str
 * @param charlist
 * @returns {string}
 */
function rtrim(str, charlist) {
    charlist = !charlist ? ' \\s\u00A0' : (charlist + '')
        .replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
    var re = new RegExp('[' + charlist + ']+$', 'g');
    return (str + '')
        .replace(re, '');
}
/**
 * 模拟PHP函数
 * @param needle
 * @param haystack
 * @param argStrict
 * @returns {boolean}
 */
function in_array(needle, haystack, argStrict) {
    var key = '',
        strict = !!argStrict;

    //we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] == ndl)
    //in just one for, in order to improve the performance
    //deciding wich type of comparation will do before walk array
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}
/**
 * 模拟PHP函数
 * @param inputArr
 * @returns {{}}
 */
function array_unique(inputArr) {
    var key = '',
        tmp_arr2 = {},
        val = '';

    var __array_search = function (needle, haystack) {
        var fkey = '';
        for (fkey in haystack) {
            if (haystack.hasOwnProperty(fkey)) {
                if ((haystack[fkey] + '') === (needle + '')) {
                    return fkey;
                }
            }
        }
        return false;
    };

    for (key in inputArr) {
        if (inputArr.hasOwnProperty(key)) {
            val = inputArr[key];
            if (false === __array_search(val, tmp_arr2)) {
                tmp_arr2[key] = val;
            }
        }
    }

    return tmp_arr2;
}
/**
 * 模拟PHP函数
 * @param needle
 * @param haystack
 * @param argStrict
 * @returns {*}
 */
function array_search(needle, haystack, argStrict) {
    var strict = !!argStrict,
        key = '';

    if (haystack && typeof haystack === 'object' && haystack.change_key_case) { // Duck-type check for our own array()-created PHPJS_Array
        return haystack.search(needle, argStrict);
    }
    if (typeof needle === 'object' && needle.exec) { // Duck-type for RegExp
        if (!strict) { // Let's consider case sensitive searches as strict
            var flags = 'i' + (needle.global ? 'g' : '') +
                (needle.multiline ? 'm' : '') +
                (needle.sticky ? 'y' : ''); // sticky is FF only
            needle = new RegExp(needle.source, flags);
        }
        for (key in haystack) {
            if (needle.test(haystack[key])) {
                return key;
            }
        }
        return false;
    }

    for (key in haystack) {
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
            return key;
        }
    }

    return false;
}
/**
 * 模拟PHP函数
 * @param delimiter
 * @param string
 * @param limit
 * @returns {*}
 */
function explode(delimiter, string, limit) {
    if (arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined')
        return null;
    if (delimiter === '' || delimiter === false || delimiter === null)
        return false;
    if (typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string ===
        'object') {
        return {
            0: ''
        };
    }
    if (delimiter === true)
        delimiter = '1';

    // Here we go...
    delimiter += '';
    string += '';

    var s = string.split(delimiter);

    if (typeof limit === 'undefined')
        return s;

    // Support for limit
    if (limit === 0)
        limit = 1;

    // Positive limit
    if (limit > 0) {
        if (limit >= s.length)
            return s;
        return s.slice(0, limit - 1)
            .concat([s.slice(limit - 1)
                .join(delimiter)
            ]);
    }

    // Negative limit
    if (-limit >= s.length)
        return [];

    s.splice(s.length + limit);
    return s;
}
/**
 * 模拟PHP函数
 * @param glue
 * @param pieces
 * @returns {*}
 */
function implode(glue, pieces) {
    var i = '',
        retVal = '',
        tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }
    if (typeof pieces === 'object') {
        if (Object.prototype.toString.call(pieces) === '[object Array]') {
            return pieces.join(glue);
        }
        for (i in pieces) {
            retVal += tGlue + pieces[i];
            tGlue = glue;
        }
        return retVal;
    }
    return pieces;
}
/**
 * 模拟PHP函数
 * @param mixed_var
 * @returns {boolean}
 */
function is_numeric(mixed_var) {
    var whitespace =
        " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
            1)) && mixed_var !== '' && !isNaN(mixed_var);
}

// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2016-01-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2016-1-2 8:9:4.18
Date.prototype.Format = function (fmt) { //author: meizz
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
//获取省市区列表
//  <select class="province" id="province-g" name="province" > </select>
//  <select class="city"    id="city-g"      name="city"     > </select>
//  <select class="area"    id="area-g"      name="area"     > </select>
//  例：get_pca(110000,110100,110108,'g');    北京市 市辖区 海淀区    'g'是id后缀
function get_pca(province_id,city_id,area_id,postfix){
    $.get("{:U('/Home/Index/getcity')}", { province_id:province_id, city_id:city_id, area_id:area_id },function(data){
        if(data.errNum==0){
            $('#province-'+postfix).html(data.retData.province);
            $('#city-'+postfix).html(data.retData.city);
            $('#area-'+postfix).html(data.retData.area);
        }else{
            showError('获取城市列表失败');
        }
    });
}
//省份改动，刷新省市区列表
$('.province').change(function () {
    var id = $(this).attr('id');
    var postfix = id.substr(id.length-1,1); //获取id后缀
    var province_id = $(this).val();
    get_pca(province_id,0,0,postfix);
})
//城市改动，刷新省市区列表
$('.city').change(function () {
    var id = $(this).attr('id');
    var postfix = id.substr(id.length-1,1); //获取id后缀
    var province_id = $(this).prev().val();
    var city_id = $(this).val();
    get_pca(province_id,city_id,0,postfix);
})

function checkInputLength(form_id){
    var noHaserror=true;
    $(form_id).find('input').each(function(){
        if($(this).attr('maxlength')){
            if($(this).val().length>$(this).attr('maxlength')){
                showError($(this).attr('tag')+'错误错误');
                noHaserror= false;
                return false;
            }
        }
        if($(this).attr('minlength')){
            if($(this).val().length<$(this).attr('minlength')){
                showError($(this).attr('tag')+'错误错误');
                noHaserror= false;
                return false;
            }
        }
        if($(this).attr('truelength')){
            if($(this).val().length!=$(this).attr('truelength')){
                showError($(this).attr('tag')+'格式错误');
                noHaserror= false;
                return false;
            }
        }
    });
    return noHaserror;
}


//更友好的时间显示
function getDateDiff(TimeStamp){
    var minute = 1000 * 60;
    var hour = minute * 60;
    var day = hour * 24;
    var halfamonth = day * 15;
    var month = day * 30;
    var now = new Date().getTime();
    var diffValue = now - TimeStamp;
    if(diffValue < 0){return;}
    var monthC =diffValue/month;
    var weekC =diffValue/(7*day);
    var dayC =diffValue/day;
    var hourC =diffValue/hour;
    var minC =diffValue/minute;
    if(monthC>=1){
        result="" + parseInt(monthC) + "月前";
    }
    else if(weekC>=1){
        result="" + parseInt(weekC) + "周前";
    }
    else if(dayC>=1){
        result=""+ parseInt(dayC) +"天前";
    }
    else if(hourC>=1){
        result=""+ parseInt(hourC) +"小时前";
    }
    else if(minC>=1){
        result=""+ parseInt(minC) +"分钟前";
    }else
        result="刚刚";
    return result;
}