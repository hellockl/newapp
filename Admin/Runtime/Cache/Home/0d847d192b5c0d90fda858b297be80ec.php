<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <title>后台操作系统</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="Thu, 19 Nov 1900 08:52:00 GMT">
    <!--Loading bootstrap css-->
    <link type="text/css" rel="stylesheet" href="/newapp/assets/vendor/font-awesome/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="/newapp/assets/vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: 'Microsoft YaHei','Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-weight: 400;
            overflow: hidden;

        }

        #signin-page {
           background: url('/newapp/assets/img/<?php echo rand(1,10);?>.jpg') center center fixed;
            -moz-background-size: cover;
            -webkit-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            padding: 5px;
            height: 1000px;
        }

        .page-form {
            width: 400px;
            margin: 5% auto 0 auto;
            background: rgba(255,255,255,.6);
            border-radius: 4px;
        }
        .page-form .input-icon i {
            position: absolute;
            margin-left: 10px;
            margin-top: 12px;
            color: #333;
        }
        .page-form input[type='text'],
        .page-form input[type='password'],
        .page-form input[type='email'],
        .page-form select {
            height: 40px;
            border-color: #e5e5e5;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            color: #333;
            padding-left:30px;
            border-radius: 0;
        }
        .page-form button{border-radius: 0;}
        .page-form .header-content {
            padding: 10px 20px;
            background: #eee;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .page-form .header-content h1 {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin: 0;
            text-transform: uppercase;
        }
        .page-form .body-content {
            padding: 15px 20px;
            position: relative;
        }
        .page-form .body-content p a {
            color: #e74c3c;
        }
        .page-form .body-content p a:hover,
        .page-form .body-content p a:focus {
            color: #333;
            text-decoration: none;
        }
        .page-form .body-content .forget-password h4 {
            text-transform: uppercase;
            font-size: 16px;
        }
        .page-form .body-content hr {
            border-color: #e0e0e0;
        }
        .page-form .state-error + em {
            display: block;
            margin-top: 6px;
            padding: 0 1px;
            font-style: normal;
            font-size: 11px;
            line-height: 15px;
            color: #d9534f;
        }
        .page-form .rating.state-error + em {
            margin-top: -4px;
            margin-bottom: 4px;
        }
        .page-form .state-success + em {
            display: block;
            margin-top: 6px;
            padding: 0 1px;
            font-style: normal;
            font-size: 11px;
            line-height: 15px;
            color: #d9534f;
        }
        .page-form .state-error input,
        .page-form .state-error select {
            background: #f2dede;
        }
        .page-form .state-success input,
        .page-form .state-success select {
            background: #dff0d8;
        }
        .page-form .note-success {
            color: #5cb85c;
        }
        .page-form label {
            font-weight: normal;
            margin-bottom: 0;
        }
        #imgVerify{
            height:38px;
            margin-left:238px;
            margin-top: -39px;
            position: absolute;
        }
    </style>
</head>

<body id="signin-page">
<div class="page-form">
    <form action="<?php echo U('checkLogin');?>" class="form" method="post" id="frm-login">
        <div class="header-content">
            <h1>欢迎</h1>
        </div>
        <div class="body-content">
            <div class="form-group">
                <div class="input-icon left"><i class="fa fa-user"></i>
                    <input type="text" placeholder="用户名" name="username" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="input-icon left"><i class="fa fa-key"></i>
                    <input type="password" placeholder="密码" name="password" class="form-control" autocomplete="off">
                </div>
            </div>
          <?php if($loginNum >= 2): ?><div class="form-group">
                  <div class="input-icon left"><i class="fa fa-barcode"></i>
                      <input type="text" placeholder="验证码" name="verify" class="form-control" autocomplete="off">
                      <img id="imgVerify" style="cursor:pointer;" src="<?php echo U('verify');?>" onclick="flushVerify();"/>

                  </div>
              </div><?php endif; ?>
            <div class="tip pull-left"></div>
            <div class="form-group pull-right">
                <!--<span  class="text-warning"><a href="#"> 忘记密码？</a>-->

                <!--</span>-->
                <button type="submit" class="btn btn-success">登入
                    <i class="fa fa-chevron-circle-right"></i>
                </button>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>
</div>
<script src="/newapp/assets/js/jquery-1.10.2.min.js"></script>
<script src="/newapp/assets/js/jquery.form.js"></script>
<script>
    $('#frm-login').ajaxForm({success:success,beforeSend:checkForm});
    function success(data){
        if(0 === data.errNum){
            $('.tip').html('<span class="text-green"><i class="fa fa-check"></i> '+ data.errMsg+'</span>');
            window.location.href=data.retData;
        }else{
            $('.tip').html('<span class="text-danger"><i class="fa fa-times"></i> '+ data.errMsg+'</span>');
            if(data.retData==2){
                var url = '<?php echo U("verify");?>';
                $('.tip').before('<div class="form-group"><div class="input-icon left"><i class="fa fa-barcode"></i><input type="text" placeholder="验证码" name="verify" class="form-control" autocomplete="off"><img id="imgVerify" style="cursor:pointer;" src="'+url+'" onclick="flushVerify();"/></div></div>');
            }
            flushVerify();
        }
    }
    function checkForm(){
        $('div.form-group').removeClass('has-error');
        if('' === $('input[name=username]').val()){
            $('.tip').html('<span class="text-danger"><i class="fa fa-times"></i> 用户名不能为空！</span>');
            $('input[name=username]').parent().parent().addClass('has-error');
            return false;
        }
        if('' === $('input[name=password]').val()){
            $('.tip').html('<span class="text-danger"><i class="fa fa-times"></i> 密码不能为空！</span>');
            $('input[name=password]').parent().parent().addClass('has-error');
            return false;
        }
        if('' === $('input[name=verify]').val()){
            $('.tip').html('<span class="text-danger"><i class="fa fa-times"></i> 验证码不能为空！</span>');
            $('input[name=verify]').parent().parent().addClass('has-error');
            return false;
        }
    }
    function flushVerify(){
        //重载验证码
        var url = "<?php echo U('verify');?>?r="+Math.floor(Math.random()*10000);
        $('#imgVerify').attr('src',url);
    }



    document.onkeydown=function(event){
        e = event ? event :(window.event ? window.event : null);
        if(e.keyCode==13){
            //checkLogin();
        }
    }
</script>

</body>
</html>