<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class PublicController extends Controller {
    protected $ret = array('errNum'=>0, 'errMsg'=>'success', 'retData'=>array());
    public function login(){

        $this->display();
    }


    /*登录ING*/
    public function checkLogin(){
        //var_dump($_POST,$_SESSION);
        $verify = new Verify();
        if (!$verify->check(I('post.code'), "sale_login")) {
            $this->error('验证码输入错误');
        }
        $where['user_name'] = I('post.user_name');
        $res = M('Users')->where($where)->find();
        if(!$res){
            $this->error('无该用户');
        }
        $where['password'] = Md5(I('post.password'));
        $result = M('Users')->where($where)->find();
        if($result['status']==1){
            $this->error('该用户还未激活');
        }
        if($result['status']==3){
            $this->error('该用户还未激活');
        }
        session("user_info",$result);
        //session("user_name",$result['user_name']);

        $this->success('登录成功',__APP__.'/Home/Index/index');
    }


    /*注册*/
    public function register(){
        if(IS_POST){
            //var_dump($_SESSION);
            $verify = new Verify();
            if (!$verify->check(I('post.code'), "sale_register")) {
                $this->error('验证码输入错误');
            }
            $usersModel = M('Users');
            if($usersModel->create()){
                $usersModel->password = Md5(I('post.password'));
                $usersModel->amount_password = Md5(I('post.amount_password'));
                $usersModel->create_time = date("Y-m-d H:i:s");

            }else{
                $this->error('注册失败！');
            }
            $res = $usersModel->add();
            if($res){
                $this->success('注册成功！管理员会与人取得联系',__APP__.'/Home/Public/login');
            }else{
                $this->error('注册失败！');
            }
        }
        $this->display();
    }


    /*退出登录*/
    public function logout(){
        session(null);
        redirect(U('login'));
    }


    public function checkUserName(){
        $user_name = I('post.user_name');
        $where['user_name'] = $user_name;
        $res = M('Users')->where($where)->find();
        //echo M('Users')->getLastSql();
        if($res){
            $this->ret['errMsg'] = '该用户名已存在！';
            $this->ret['errNum'] = 0;

        }else{
            $this->ret['errMsg'] = 'ok';
            $this->ret['errNum'] = 1;

        }
        $this->ajaxReturn($this->ret);
    }

    public function checkPhone(){
        $phone = I('post.phone');
        $where['phone'] = $phone;
        $res = M('Users')->where($where)->find();
        if($res){
            $this->ret['errMsg'] = '该手机号已存在！';
            $this->ret['errNum'] = 0;

        }else{
            $this->ret['errMsg'] = 'ok';
            $this->ret['errNum'] = 1;

        }
        $this->ajaxReturn($this->ret);
    }


    /**
     * 验证码获取
     * hello word
     * @date: 2017-02-14
     */
    public function verify()
    {
        $config = array(
            'fontSize' => 20,
            'length' => 4,
            'useCurve' => false,
            'useNoise' => true,
            'reset' => true
        );
        $Verify = new Verify($config);
        $Verify->entry("sale_login");
    }

    public function verify_register(){
        $config = array(
            'fontSize' => 20,
            'length' => 4,
            'useCurve' => false,
            'useNoise' => true,
            'reset' => true
        );
        $Verify = new Verify($config);
        $Verify->entry("sale_register");
    }

}