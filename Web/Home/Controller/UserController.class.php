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
    
    
    public function userinfo(){
        $this->display();
    }
    
    public function upload_a(){
        if(IS_POST){
            $img = $_FILES['file1'];
            
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   = 3145728 ;// 设置附件上传大小
            $upload->exts      = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  = './Public/'; // 设置附件上传根目录
            $upload->savePath  = 'upload/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->uploadOne($img);


            if(!$info) {// 上传错误提示错误信息
                echo json_encode(array('status' => 'error','msg' => $upload->getError()));
                exit;
            }else{// 上传成功

                $imgpath = $info['savepath'].$info['savename'];
                echo json_encode(array('status' => 'success','url'=>'/Public/'.$imgpath));
                exit;
            }

        }else{
            $this->display();
        }
    }
    public function upload_b(){
        if(IS_POST){
            $img = $_FILES['file2'];

            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   = 3145728 ;// 设置附件上传大小
            $upload->exts      = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  = './Public/'; // 设置附件上传根目录
            $upload->savePath  = 'upload/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->uploadOne($img);


            if(!$info) {// 上传错误提示错误信息
                echo json_encode(array('status' => 'error','msg' => $upload->getError()));
                exit;
            }else{// 上传成功

                $imgpath = $info['savepath'].$info['savename'];
                echo json_encode(array('status' => 'success','url'=>'/Public/'.$imgpath));
                exit;
            }

        }else{
            $this->display();
        }
    }


}