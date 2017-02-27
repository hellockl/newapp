<?php
namespace  Home\Controller;
/**
 *控制面板 模块
 * @author <dingwenzai@fangjinsuo.com>
 * @createdate: 2016-08-09
 */
//use Home\Controller\BaseController;
use Think\Controller;
//use Api\Controller\MessageController as Message;

class IndexController extends BaseController {

    /**
     * 进入首页
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     */
    public function index()
    {
        $this->assign('user_id',$this->user_id);
        $this->assign('user_name',$this->user_name);
        //$this->assign('user_info',session('user_info'));//登陆用户session中所有信息
        //$menu = session('menu');
		$menu = array(
		
           "我的统计" =>
          array(
             "action_id" =>"97",
             "controller_name" =>"DataCenter",
             "action_name" =>"Index",
             "action_title" =>"我的统计",
             "action_icon" =>"fa-bar-chart-o",
             "action_sort" =>"9999",
             "action_status" =>"1",
             "action_display" =>"1",
             "module_id" =>"7",
             "icon" =>"fa-cubes",
             "module_name" =>"Sale",
             "module_title" => "销售中心",
             "module_group" => "sale",
             "module_sort" =>"999",
             "module_status" => "1",
             "module_display" =>"1",
             "url" => "Index/index"
          ),
           "客户" =>
          array(
            "action_id"=> "11",
             "controller_name" =>"Customer",
             "action_name" =>"index",
             "action_title" =>"客户",
             "action_icon" =>"fa-users",
             "action_sort" => "255",
             "action_status" => "1",
             "action_display" =>"1",
             "module_id" => "7",
             "icon" => "fa-cubes",
               "module_title" => "销售中心",
             "module_group" => "sale",
             "module_sort" => "999",
             "module_status" =>"1",
             "module_display" => "1",
			"url"=> "index/index"
         )
			
		);
        $this->assign('menu_list', $menu );
       // $MSG = new Message();
        //$MSG->send('18616968599','房金所客服将新客户分配给了你,客户电话:18616968599,请注意跟进');
       // $MSG->send('18616968599','房金所客服将新客户分配给了你,客户电话:'.''.',请注意跟进');
        $this->display();
    }
    /**
     * 获取城市
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return json数据
     */
    public function getcity()
    {
        $result = D('User')->getCity(I('get.province_id'),I('get.city_id'),I('get.area_id'));
        $this->ajaxReturn($result);
    }
    /**
     * 修改密码
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-14
     */
    public function editPassword(){
        if(IS_POST){
            $post = I('post.');
            $post ['user_id']  == $_SESSION ['user_info'][  'user_id' ];
            $result = D('User')->editPassword($post);
            $this->ajaxReturn($result);
        }else{
            $this->display();
        }
    }
    
    public function help(){
       $help_url = C('HELP_URL');
        redirect($help_url);
    }
}