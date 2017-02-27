<?php
namespace  Home\Controller;
use Home\Controller\BaseController;
//use Home\Logic\ContracttLogic;
use Home\Model\UserModel;

/**
* 业绩管理 模块
* @author <zengzhiqiang@fangjinsuo.com>
* @createdate: 2016-08-12
*/
class AchievementController extends BaseController {
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * 业绩首页
    * @author <zengzhiqiang@fangjinsuo.com>
    * @createdate: 2016-08-12
    */
    public function index(){
        $this->display();
    }
    /**
     * 业绩列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-12
     * @param get方式-查询条件
     */
    public function table(){
        $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"));
        $users=implode(',',$users_arr);

        $result = D('Achievement')->achievementList(I('get.'),$users);
        $this->assign('myUserid' , session("user_info.user_id"));
        $this->assign('achievement_list' , $result[0]);
        $this->assign('pageBar', $result[1]);
        $this->display();
    }
    /**
     * 确认业绩
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-15
     * @param get id业绩的id号
     * @return json数据
     */
    public function check(){
        $return=D('Achievement')->achievementCheck(I('get.id'));
        if($return===true){
            $this->setjsonReturn('操作成功');
        }elseif($return==false){
            $this->errorjsonReturn('操作失败');
        }else{
            $this->errorjsonReturn($return);
        }
    }
}