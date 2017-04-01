<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        //$user_info = M('Users')->where("user_id=".$_SESSION['users_info']['user_id'])->find();
        
        $give_help = M("Givehelp")->field("sum(amount) as total_money,count(*) as givecount")->where("user_id=".$_SESSION['users_info']['user_id'])->select();
        $get_help = M("Gethelp")->field("sum(amount) as total_money,count(*) as getcount")->where("user_id=".$_SESSION['users_info']['user_id'])->select();
        $newslist = M("News")->where()->order("create_time asc")->limit(5)->select();
        $this->assign("newslist",$newslist);
        $this->assign('give_help',$give_help[0]);
        $this->assign('get_help',$get_help[0]);
        //$this->assign('user_info',$user_info);
        $this->display();
    }

    public function test(){
        
        $this->assign('user_info',$_SESSION['users_info']);
        $this->display();
    }
    
    public function giveHelpList(){
        $Givehelp = M('Givehelp');
        $where['user_id'] = $_SESSION['users_info']['user_id'];
        $count = $Givehelp->where($where)->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, 5);
        $show = $Page->show(); // 分页显示输出
        $helplist = $Givehelp->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order("create_time desc")->select();
        foreach ($helplist as $k=>$v){
            
            
            $time = time() - $v['create_time'];
            if($v['status'] == 4){
                $helplist[$k]['totay_sy'] = 0;
                $helplist[$k]['total_sy'] = 0.198*$v['amount'];
            }else{
                if($time>=86400){
                    $helplist[$k]['today_sy'] = $v['amount']*0.0066;
                }else{
                    $helplist[$k]['today_sy'] = 0;
                }
                $helplist[$k]['total_sy'] = $this->secsToStr($time)*$v['amount']*0.0066;
            }

            if($v['status']==0){
                
                $helplist[$k]['status_name'] = '未匹配';
            }else if($v['status']==1){
                $helplist[$k]['status_name'] = '已匹配，未支付';
            }else if($v['status']==2){
                $helplist[$k]['status_name'] = '已匹配，已支付';
            }else if($v['status']==3){
                $helplist[$k]['status_name'] = '已确认打款';
            }else {
                $helplist[$k]['status_name'] = '已完成';
            }
        }
        //var_dump($helplist);
        $this->assign("show", $show);
        $this->assign('helplist',$helplist);
        $this->display();
    }
    public function secsToStr($secs) {
        if($secs>=86400){
            $days = floor($secs/86400);
        }else{
            $days = 0;    
        };
        
        
        
        return $days;
    }
    /**
     * @description ：匹配列表
     *
     *
     */
    public function matchList(){
        $givehelp_id = I('givehelp_id',0,'intval');
        $where['givehelp_id'] = $givehelp_id;
        $match_list =  M('Gethelp')->alias("G")->field("G.*,U.user_name,U.alipay,U.wechat,U.name,U.phone")->join("LEFT JOIN ".C('DB_PREFIX')."users U on(U.user_id=G.user_id)")->where($where)->select();
        foreach($match_list as $key=>$val){
            
            if($val['status']==0){
                $match_list[$key]['status_name'] = "未匹配";
            }else if($val['status']==1){
                $match_list[$key]['status_name'] = "已匹配,未打款";
            }else if($val['status']==2){
                $match_list[$key]['status_name'] = "已匹配,已打款";
            }
        }
        //var_dump($match_list);
        $this->assign('givehelp_id',$givehelp_id);
        $this->assign('match_list',$match_list);
        $this->display();
    }
    
    /**
     * @description ：确认提供帮助已全都打款
     */
    public function confirmMoney(){
        $givehelp_id = I('givehelp_id',0,'intval');
        $where['id'] = $givehelp_id;
        $givehelp_modle = M("Givehelp");
        $result = $givehelp_modle->where($where)->getField('status');
        if($result['status']==0){
            $this->ajaxError('该帮助还未匹配，请先匹配');
        }
        $res = M("Givehelp")->where($where)->setField('status',2);
        if($res){
            $this->ajaxReturn(array('status'=>'ok','msg'=>'操作成功'));
        }else{
            $this->ajaxReturn(array('status'=>'error','msg'=>'操作失败'));
        }
    }
    
    /**
     * @description ：确认得到帮助已得到款
     */
    public function confirmAccept(){
        $gethelp_id = I('gethelp_id',0,'intval');
        $where['id'] = $gethelp_id;
        $gethelp_modle = M("Gethelp");
        $result = $gethelp_modle->where($where)->getField('status');
        if($result['status']==0){
            $this->ajaxReturn(array('status'=>'error','msg'=>'该帮助还未匹配，请等待匹配'));
        }
        $res = M("Gethelp")->where($where)->setField('status',2);
        if($res){
            $this->ajaxReturn(array('status'=>'ok','msg'=>'操作成功'));
        }else{
            $this->ajaxReturn(array('status'=>'error','msg'=>'操作失败'));
        }
    }
    
    public function getHelpList(){

        $GetHelp = M('Gethelp');
        $where['G.user_id'] = $_SESSION['users_info']['user_id'];
        $count = $GetHelp->alias("G")->where($where)->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, 8);
        $show = $Page->show(); // 分页显示输出
        $helplist = $GetHelp->alias("G")->field("G.*,U.user_name,U.alipay,U.wechat,U.name,U.phone,H.user_name as givehelp_user")
                ->join("LEFT JOIN ".C('DB_PREFIX')."users U on(U.user_id=G.user_id)")
                ->join("LEFT JOIN ".C('DB_PREFIX')."users H on(G.givehelp_uid=H.user_id)")
                ->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order("G.create_time desc")->select();
        foreach ($helplist as $key=>$val){
                if($val['status']==0){
                        $helplist[$key]['status_name'] = "未匹配";
                    }else if($val['status']==1){
                        $helplist[$key]['status_name'] = "已匹配,未打款";
                    }else if($val['status']==2){
                        $helplist[$key]['status_name'] = "已匹配,已打款";
                    }
        }
        $this->assign("show", $show);
        $this->assign('helplist',$helplist);
        $this->display();
    }


    public function givehelp(){
        if(IS_POST){
            $amount = I('post.amount');
            $amount_password = I('post.amount_password');
        }
        $this->display();
    }
    
    public function checkGiveHelp(){
        $amount_password = I('post.amount_password');
        
        $where['user_id'] = $_SESSION['users_info']['user_id'];
        $res = M('Users')->field('amount_password,status,is_forbid')->where($where)->find();
        if($res['status']!=2){
            $this->ajaxReturn(array('errorMsg'=>"请先去审核个人信息",'errorCode'=>4));
        }
        //echo M('Users')->getLastSql();
        //var_dump($res, md5($amount_password),$amount_password);
        if($res['amount_password'] == md5($amount_password)){
            $data['amount'] = I('post.amount');
            $data['create_time'] = time();
            $data['user_id'] = $_SESSION['users_info']['user_id'];
            //10才能添加一次帮 助
            $givehelp_modle = M('Givehelp');
            $last_givehelp =  $givehelp_modle->where($where)->order("create_time desc")->find();
            if(!empty($last_givehelp)){
                $time_long = time() - $last_givehelp['create_time'];
                if($time_long<=86400){
                    $this->ajaxReturn(array('errorMsg'=>"10天后才能发布下个需求！",'errorCode'=>3));
                }
            }
            $resu = M('Givehelp')->add($data);
            if($resu){
                $resoult['errorMsg'] = '发布成功！';
                $resoult['errorCode'] = 0;
            }else{
                $resoult['errorMsg'] = '发布失败！';
                $resoult['errorCode'] = 2;
            }
        }else{
            $resoult['errorMsg'] = '支付密码错误';
            $resoult['errorCode'] = 1;

        }
        $this->ajaxReturn($resoult);
    }
    
    
    public function checkGetHelp(){
        $amount_password = I('post.amount_password');
        
        $where['user_id'] = $_SESSION['users_info']['user_id'];
        $res = M('Users')->where($where)->getField('amount_password');
        //echo M('Users')->getLastSql();
        //var_dump($res, md5($amount_password),$amount_password);
        if($res == md5($amount_password)){
            $data['amount'] = I('post.amount');
            $data['create_time'] = time();
            $data['user_id'] = $_SESSION['users_info']['user_id'];
            //10才能添加一次帮 助
            $gethelp_modle = M('Gethelp');
            $last_gethelp =  $gethelp_modle->where($where)->order("create_time desc")->find();
            if(!empty($last_gethelp)){
                $time_long = time() - $last_gethelp['create_time'];
                if($time_long<=86400){
                    $this->ajaxReturn(array('errorMsg'=>"10天后才能发布下个需求！",'errorCode'=>3));
                }
            }
            $resu = M('Gethelp')->add($data);
            if($resu){
                $resoult['errorMsg'] = '发布成功！';
                $resoult['errorCode'] = 0;
            }else{
                $resoult['errorMsg'] = '发布失败！';
                $resoult['errorCode'] = 2;
            }
        }else{
            $resoult['errorMsg'] = '支付密码错误';
            $resoult['errorCode'] = 1;

        }
        $this->ajaxReturn($resoult);
    }
    
    public function news(){
        $this->display();
    }

}