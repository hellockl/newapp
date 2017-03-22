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
        if($result){
            if($result['status']==1){
                $this->error('该用户还未激活');
            }
            if($result['is_forbid']==3){
                $this->error('该用户已被禁用');
            }
            $result['online_num'] = M("OnlineNum")->where("id=1")->getField('online_num');
            session("users_info",$result);
            $this->success('登录成功',__APP__.'/Home/Index/index');
        }else{
            $this->error('密码错误！');
        }
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
                if(!empty(I('post.referee_name','','trim'))){
                    $father_info = $usersModel->where("user_name='".I('post.referee_name')."'")->find();
                    $usersModel->father_id = $father_info['user_id'];
                    if(!empty($father_info['father_id'])){
                        $usersModel->grand_id = $father_info['father_id'];
                    }
                }
                $usersModel->password = Md5(I('post.password'));
                $usersModel->amount_password = Md5(I('post.amount_password'));
                $usersModel->create_time = time();

            }else{
                $this->error('注册失败！');
            }
            $res = $usersModel->add();
            if($res){
                $this->success('注册成功！管理员会与人取得联系',__APP__.'/Home/Public/login');
            }else{
                $this->error('注册失败！');
            }
        }else{
            $this->assign("referee_name",$_GET['recomend']);
            $this->display();
        }

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