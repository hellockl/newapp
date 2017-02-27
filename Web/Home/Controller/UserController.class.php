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
        $user_id = $_SESSION['user_info'];
        $usersModel = M('usersModel');
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
     *修改密码
     *
     */
    public function editPassword(){
        
        $this->display();
    }




}