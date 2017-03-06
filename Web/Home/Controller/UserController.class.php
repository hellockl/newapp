<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {

    public function index(){
        $this->assign('nav',"user");
        $this->display();
    }
    /**
     * 编辑个人信息
     *
     */
    public function editUser(){
        $this->assign('nav',"user");
        $user_id = $_SESSION['users_info'];
        $usersModel = M('Users');
        if($usersModel->create()){

        }
    }

    public function checkUserName(){
        $user_name = I('post.user_name');
        $where['user_name'] = $user_name;
        $res = M('Users')->where($where)->find();
        //echo M('Users')->getLastSql();
        if($res){
            $this->ret['errMsg'] = '该用户名已存在！';
            $this->ret['errorCode'] = 201;

        }else{
            $this->ret['errMsg'] = 'ok';
            $this->ret['errorCode'] = 1;

        }
        $this->ajaxReturn($this->ret);
    }
    public function checkPhone(){
        $phone = I('post.phone');
        $where['phone'] = $phone;
        $res = M('Users')->where($where)->find();
        if($res){
            $this->ret['errMsg'] = '该手机号已存在！';
            $this->ret['errorCode'] = 201;

        }else{
            $this->ret['errMsg'] = 'ok';
            $this->ret['errorCode'] = 1;

        }
        $this->ajaxReturn($this->ret);
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
            $this->assign('nav',"user");
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
            $this->assign('nav',"user");
            $this->display();
        }
        
    }
    
    
    public function userinfo(){
        //var_dump($_SESSION);
        if(IS_POST){
            $user_info =array(
                'name'=>I("post.name",'','trim'),
                'idcard'=>I("post.idcard",'','trim'),
                'alipay'=>I("post.alipay",'','trim'),
                'wechat'=>I("post.wechat",'','trim'),
                'bank'=>I("post.bank",'','trim'),
                'bank_card'=>I("post.bank_card",'','trim'),
               
            );
            
            $res = M("Users")->where('user_id='.$_SESSION['users_info']['user_id'])->save($user_info);
            //var_dump($user_info);
            //echo M("Users")->getLastSql();
            if($res!==false){
                $this->ajaxReturn(array('errorCode'=>0,'errorMsg'=>'操作成功！'));
            }else{
                $this->ajaxReturn(array('errorCode'=>1,'errorMsg'=>'操作失败！'));
            }
        }else{
            $this->assign('nav',"user");
            $user_info = M("Users")->where('user_id='.$_SESSION['users_info']['user_id'])->find();
            $this->assign('userinfo',$user_info);
            $this->display();
        }
        
    }
    
    public function updateImg(){
        if(IS_POST){
            $user_info =array(
                'idcard_imga'=>I("post.idcard_imga",'','trim'),
                'idcard_imgb'=>I("post.idcard_imgb",'','trim'),
            );
            $res = M("Users")->where('user_id='.$_SESSION['users_info']['user_id'])->save($user_info);
            if($res!==false){
                $this->ajaxReturn(array('errorCode'=>0,'errorMsg'=>'操作成功！'));
            }else{
                $this->ajaxReturn(array('errorCode'=>1,'errorMsg'=>'操作失败！'));
            }
        }else{
            $this->ajaxReturn(array('errorCode'=>1,'errorMsg'=>'操作失败！'));
        }
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

    public function recommend(){
        $this->assign('nav',"user");
        $this->display();
    }

}