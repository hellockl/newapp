<?php
namespace Home\Controller;
use Think\Controller;
class CrontabController extends Controller {
    
    
    public function push(){
        //$News = M('News');
        $givehelp_model = M('Givehelp');
        $time = time()-2592000;
        $givehelp_list = $givehelp_model->where('create_time>='.$time.' AND status=3')->select();
        if(!empty($givehelp_list)){
            $gethelp_model = M('Gethelp');
            foreach($givehelp_list as $key=>$val){
                $res = $givehelp_model->where("id=".$val['id'])->setField("status",4);
                $user_info = M("Users")->where('user_id='.$val['user_id'])->find();
                $data_a = array(
                    'user_id'=>$val['user_id'],
                    'amount'=>$val['amount']*1.2,
                    'create_time'=>time(),
                    'status'=>0,
                    'type'=>1
                );
                $res2 = $gethelp_model->add($data_a);

                //上一级得到 本金*10%
                if($user_info['father_id']){
                    $data_b = array(
                        'user_id'=>$user_info['father_id'],
                        'amount'=>$val['amount']*0.1,
                        'create_time'=>time(),
                        'status'=>0,
                        'type'=>2
                    );
                   $res3 = $gethelp_model->add($data_b);
                }

                //上上级得到 本金*3%
                if($user_info['grand_id']){
                    $data_c = array(
                        'user_id'=>$user_info['grand_id'],
                        'amount'=>$val['amount']*0.03,
                        'create_time'=>time(),
                        'status'=>0,
                        'type'=>3
                    );
                    $res3 = $gethelp_model->add($data_c);
                }
            }
        }
        echo time();
        echo "<br>";
        echo "ok";
        file_put_contents('E:/a.txt',time()."\n",FILE_APPEND);

    }
    


}