<?php
namespace  Home\Controller;
use Home\Controller\BaseController;
/**
 * 业务员管理模块
 * @author <zengzhiqiang@fangjinsuo.com>
 * @createdate: 2016-10-25
 */
class UserController extends BaseController {

    public function index(){
        $city_list = array();
        if($_SESSION['user_info']['data_type'] == 4){
            $city_list = C("SALECITY");
        }else{
            $city_list[0] = substr($_SESSION['user_info']['sub_company_name'],0,6);
        }
        $this->assign('city_list' , $city_list);
        $this->display();
    }

    /**
     * 查询回款记录列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-10-25
     */
    public function table(){
        $result = D('User')->UserList();    //业务员队列
        $this->assign('user_list' , $result);
//        echo '<pre>';
//        print_r($_SESSION['user_info']);
//        print_r($result);
        $this->display();
    }

    public function userRedis(){
        $result = D('User')->userRedis(I('get.city'));
        //$this->ajaxReturn($result);
        $this->assign('redis_list' , $result['retData']);
        $this->display();
    }
    public function del_redis(){
        $result = D('User')->del_redis(I('get.'));
        $this->ajaxReturn($result);
    }
    public function add_redis(){
        $result = D('User')->add_redis(I('get.'));
        $this->ajaxReturn($result);
    }
    public function getUserTable(){
        $get = I('get.');
        $get['user_ids'] = D('Contract')->getSubUserByUserId(session("user_info.user_id"),'all');
        $result = D('User')->getUserTable($get);
        if(empty($result[0])){
            $this->ajaxReturn(array('status'=>1,'info'=>'获取数据失败','data'=>array()));
        }else{
            $data['list'] = $result[0];
            $data['p'] = I('get.p') ? I('get.p') : 1;
            $data['count'] = $result[2];
            $data['total'] = $data['count']%5 ? intval($data['count']/5)+1 : $data['count']/5;
            $data['where'] = $result[3];
            $this->ajaxReturn(array('status'=>0,'info'=>'获取数据成功','data'=>$data));
        }
    }
}