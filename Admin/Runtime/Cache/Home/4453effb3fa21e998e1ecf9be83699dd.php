<?php if (!defined('THINK_PATH')) exit();?><style>
    .box{border-top: solid #00b7ee 5px}
</style>
<div class="wrapper">
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div id="ajax-content1"> </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var link = "<?php echo U('indexTable');?>";
        loadURL(link,'#ajax-content1');
    });

    $('#frmSearch').ajaxForm({beforeSend:checkForm,success:success});
    function checkForm(){

    }
    function success(data){
        $('#ajax-content').html(data);
    }
</script>