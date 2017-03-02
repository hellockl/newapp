<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {
    public function index(){
        $this->display();
    }
    /**
     * 编辑个人信息
     *
     */
    public function editUser(){
        $user_id = $_SESSION['users_info'];
        $usersModel = M('Users');
        if($usersModel->create()){

        }
    }

    /**
     * 发布需求
     *
     */
    public function addHelp(){
        $amount = $_POST['amount'];
        $amount_password = $_POST['amount_password'];

    }

    /**
     *修改登录密码
     *
     */
    public function editPassword(){
        if(IS_POST){
            $where['user_id'] = $_SESSION['users_info']['user_id'];
            $where['password'] = md5(I('post.old_password','','trim'));
            $res = M('Users')->where($where)->find();
            
            if(!$res){
                $this->ajaxReturn(array('errCode'=>2,'errorMsg'=>'原密码输入错误！'));
            }
            $where['user_name'] = I("post.username",'','trim');
            
            $user_info = M('Users')->where($where)->setField('password',  md5(I('post.password','','trim')));
            if($user_info){
                $this->ajaxReturn(array('errCode'=>0,'errorMsg'=>'修改密码成功！'));
            }else{
                $this->ajaxReturn(array('errCode'=>1,'errorMsg'=>'修改密码失败！'));
            }
            
        }else{
            $this->display();
        }
        
    }
    
    /**
     *修改资金密码
     *
     */
    public function editAmountPassword(){
        if(IS_POST){
            $where['user_id'] = $_SESSION['users_info']['user_id'];
            
            
            $where['user_name'] = I("post.username",'','trim');
            
            $user_info = M('Users')->where($where)->setField('amount_password',  md5(I('post.password','','trim')));
            if($user_info){
                $this->ajaxReturn(array('errCode'=>0,'errorMsg'=>'修改密码成功！'));
            }else{
                $this->ajaxReturn(array('errCode'=>1,'errorMsg'=>'修改密码失败！'));
            }
            
        }else{
            $this->display();
        }
        
    }




}