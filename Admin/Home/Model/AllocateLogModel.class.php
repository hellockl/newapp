<?php

namespace Home\Model;

use Think\Model;

/**
 * Created by PhpStorm.
 * Date: 2016/8/8
 * Time: 11:30
 */
class AllocateLogModel extends Model
{

    /**
     * 添加或者修改数据
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $post
     * @return bool
     */
    function addOrSave($post=array()){
        $post['create_user_id']=session('user_info.user_id');
        $post['create_user_name']=session('user_info.name');;
        $post['create_time']=time();
        if($post['allocate_id']){
            return $this->save($post)?true:false;
        }else{
            return $this->add($post)?true:false;
        }
    }


    /**
     * 通过操作方式写入日志
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $action
     * @param $customerInfo
     * @param $return
     * @return bool
     */
    function addLogByAction($action,$customerInfo,$return){
        //记录领取操作操作
        if($action=='lookInfo'){
            $allocatelog=array('operation'=>'查看号码','customer_id'=>$customerInfo['customer_id'],
                'customer_name'=>$customerInfo['customer_name'],'old_owner_id'=>$customerInfo['owner_user_id'],
                'new_owner_id'=>$customerInfo['owner_user_id'],'create_user_id'=>session('user_info.user_id'),
                'create_user_name'=>session('user_info.name'), 'create_time'=>time(),'return'=>$return);
        }elseif($action=='receive'){
            $allocatelog=array('operation'=>'领取客户','customer_id'=>$customerInfo['customer_id'],
                'customer_name'=>$customerInfo['customer_name'],'old_owner_id'=>0,'new_owner_id'=>session('user_info.user_id'),
                'create_time'=>time(),'create_user_id'=>session('user_info.user_id'),'create_user_name'=>session('user_info.name'),'return'=>$return);
        }elseif($action=='removeCustomer'){
            $allocatelog=array('operation'=>'扔回公盘','customer_id'=>$customerInfo['customer_id'],
                'customer_name'=>$customerInfo['customer_name'],'old_owner_id'=>$customerInfo['owner_user_id'],'new_owner_id'=>0,
                'create_time'=>time(),'create_user_id'=>session('user_info.user_id'),'create_user_name'=>session('user_info.name'),'return'=>$return);
        }
        if($this->add($allocatelog)){return true;}else{return false;}
    }

    function selectCustomerWayLog($customer_id){
        $where=array();
        $where['customer_id']=array('eq',$customer_id);
        $where['operation']=array('in',array('客户清洗','自动分配','手动分配','领取客户','客户分配','扔回公盘'));
        $info=$this->where($where)->order('create_time desc')->select();
        $userModel=new UserModel();
        foreach($info as $key=>$value){
            $info[$key]['old_owner_name']=$value['old_owner_id']!=0?$userModel->getUserInfoByID($value['old_owner_id'])['name']:'空';
            $info[$key]['new_owner_name']=$value['new_owner_id']!=0?$userModel->getUserInfoByID($value['new_owner_id'])['name']:'空';
            $info[$key]['create_user_name']=$value['create_user_id']!=0?$userModel->getUserInfoByID($value['create_user_id'])['name']:'空';
        }
        return $info;
    }

    public function receiveCountByWhere($user_id){
        $user_id = intval($user_id);
        $count = 0;
        if($user_id > 0){
            $a = strtotime(date("Y-m-d",time()));
            $b=$a+86400;
            $where['create_time']=array('between',$a.','.$b);
            $where['operation']=array('eq','领取客户');
            $where['create_user_id']=array('eq',$user_id);
            $count =$this->where($where)->count();
        }
        return $count;
    }
}