<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $user_info = M('Users')->where("user_id=".$_SESSION['user_info']['user_id'])->find();
        //var_dump($user_info);
        $this->assign('user_info',$user_info);
        $this->display();
    }

    public function test(){
        
        $this->assign('user_info',$_SESSION['user_info']);
        $this->display();
    }
    
    public function giveHelpList(){
        $Givehelp = M('Givehelp');
        $where['user_id'] = $_SESSION['user_info']['user_id'];
        $count = $Givehelp->where($where)->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, 1);
        $show = $Page->show(); // 分页显示输出
        $helplist = $Givehelp->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order("create_time desc")->select();
        $this->assign("show", $show);
        $this->assign('helplist',$helplist);
        $this->display();
    }
    
    public function getHelpList(){
        $GetHelp = M('Gethelp');
        $where['user_id'] = $_SESSION['user_info']['user_id'];
        $count = $GetHelp->where($where)->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, 1);
        $show = $Page->show(); // 分页显示输出
        $helplist = $GetHelp->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order("create_time desc")->select();
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
        
        $where['user_id'] = $_SESSION['user_info']['user_id'];
        $res = M('Users')->where($where)->getField('amount_password');
        //echo M('Users')->getLastSql();
        //var_dump($res, md5($amount_password),$amount_password);
        if($res == md5($amount_password)){
            $data['amount'] = I('post.amount');
            $data['create_time'] = time();
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
    
    
    public function news(){
        $this->display();
    }

}