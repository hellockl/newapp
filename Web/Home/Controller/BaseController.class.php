<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller
{
    protected $user_id,$user_name,$user_info;
    protected $ret = array('errNum'=>0, 'errMsg'=>'success', 'retData'=>array());
    public function __construct() {
        parent::__construct();
        $user_info = $_SESSION['users_info'];
        //var_dump($user_info);
        $this->assign('user_info',$_SESSION['users_info']);
        if (!$user_info) {
            redirect(U('Public/login'));
        }

    }
    /**
     * 获取操作权限
     * @author: hello
     * @date: 2016-07-20
     */
    function getNoAuthorize(){
        $url = 'Sale/'.CONTROLLER_NAME.'/'.ACTION_NAME;
        if(in_array_case($url,$_SESSION['no_authority'])){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 返回正确的格式的json数据
     * @param $value 要json转换的数据
     * @param null $key 数据对应的关键词(可选)
     * @return json数据
     */
    function setjsonReturn($value,$key=null){
        if (isset($key))
            $this->ret['retData'][$key]=$value;
        else
            $this->ret['retData']=$value;
        $this->ajaxReturn ($this->ret,'JSON');
    }
    /**
     * 返回错误格式的json数据
     * @param $value
     * @return json数据
     */
    function errorjsonReturn($value){
        $this->ret['errNum']=-1;
        $this->ret['errMsg']=$value;
        $this->ajaxReturn ($this->ret,'JSON');
    }
}