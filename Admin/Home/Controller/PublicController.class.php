<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;

class PublicController extends Controller
{
    protected $ret = array('errNum'=>0, 'errMsg'=>'success', 'retData'=>array());
    /**
     * 登录界面
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     */
    public function login()
    {
        if(session('user_info')) {
            redirect(U('Index/index'));
            die();
        }
        $loginNum = session('loginNum') ? session('loginNum') : 0;
        $this->assign('loginNum', $loginNum);
        $this->display();
    }
    /**
     * 登录校验
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @return json对象
     */
    public function checkLogin()
    {
        $verify = new Verify();
        $loginNum = session('loginNum') ? session('loginNum') : 0;
        if($loginNum >2){
            if (!$verify->check(I('post.verify'), "sale_login")) {
                if(session('?loginNum')){
                    $loginNum = session('loginNum');
                    session('loginNum',$loginNum+1);
                }else{
                    session('loginNum',1);
                }
                $this->ajaxReturn(array('errNum'=>4000,'errMsg'=>'验证码错误','retData'=>''));
            }
        }
        $arr=array("username"=>I('post.username'),"password"=>I('post.password'),'system'=>'sale');
		/*
        $return=post_url(C('BASE_LOGIN'),$arr);
        file_put_contents("login.log",print_r($return,true));
        $json = json_decode($return,true);
        */
		if (1){
            session('loginNum',null);
            //获取自己的角色名
			/*
            $role_arr=array_filter(explode(',',$json['user_info']['role_ids']));
            $role_name_arr=array();
            foreach($role_arr as $value){
                $roleInfo=D('Contract')->getRoleInfoBy('role_id',$value);
                $role_name_arr[$value]=$roleInfo['name'];
            }
            $json['user_info']['role_names']=$role_name_arr;
            session("user_info",$json['user_info']);
            session("menu",$json['menu']);
            session("no_authority",$json['authority']);
			*/
            $this->ret['errMsg'] = '欢迎回来！';
            $this->ret['retData'] = U('index/index');
        }else{
            if(session('?loginNum')){
                $loginNum = session('loginNum');
                session('loginNum',$loginNum+1);
            }else{
                session('loginNum',1);
            }
            $loginNum = session('loginNum') ? session('loginNum') : 0;
//            $this->ret['errNum'] = 4001;
//            $this->ret['errMsg'] = '用户名或密码错误！';
            $this->ret['errNum'] = $json['errNum'];
            $this->ret['errMsg'] = $json['errMsg'];
            $this->ret['retData'] = $loginNum;
        }
        $this->ajaxReturn($this->ret);
    }
    /**
     * 退出登录
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     */
    public function logout(){
        session(null);
        redirect(U('login'));
    }
    /**
     * 验证码获取
     * @author: <jie@fangjinsuo.com>
     * @date: 2016-08-5
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
}