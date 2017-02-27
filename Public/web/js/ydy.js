$(function(){
    function getProvide(){
        $.common.ajaxPost('/user/Community/newProvide' , {
            
        } , function($data) {
            if($data.provide.errorCode == 0){
                if($data.provide.result=='' || $data.provide.result==[]) {
                    return false;
                }
                $provideData = $data.provide.result;
                if(parseInt($provideData.money) >= 10000) {
                    money = parseInt($provideData.money)/10000 + "万元";
                } else {
                    money = $provideData.money + "元";
                }
                $("#proivde_money").html(money);
                $("#proivde_status").html($provideData.status);
                $("#proivde_date").html($provideData.create_time);
            }else if($data.provide.errorCode==11){
                $(".not1").html($data.provide.errorMsg).css("marginLeft","120px").show();
            }

            if($data.accepthelp.errorCode == 0){
                if($data.accepthelp.result=='' || $data.accepthelp.result==[]) {
                    return false;
                }
                $accepthelpData = $data.accepthelp.result;
                if(parseInt($accepthelpData.money) >= 10000) {
                    money = parseInt($accepthelpData.money)/10000 + "万元";
                } else {
                    money = $accepthelpData.money + "元";
                }
                $("#accepthelp_money").html(money);
                $("#accepthelp_status").html($accepthelpData.status);
                $("#accepthelp_date").html($accepthelpData.create_time);
            }else if($data.accepthelp.errorCode ==11){
                $(".not2").html($data.accepthelp.errorMsg).css("marginLeft","120px").show();
            }
        });
     }
    getProvide();
})
