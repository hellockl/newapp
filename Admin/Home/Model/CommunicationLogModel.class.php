<?php
/**
 * Created by PhpStorm.
 * User: ckl
 * Date: 2016/9/26
 * Time: 21:06
 */

namespace Home\Model;
use Think\Model;
class CommunicationLogModel extends Model
{
    /**
     * 获取沟通日志列表
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param $customer_id
     * @return array
     */
    public function communicationLogList($customer_id){
        $where['customer_id'] = $customer_id;
        $result = $this->where($where)->order('create_time desc')->select();
        $userModel=new UserModel();
        foreach($result as $key=>$value){
            $result[$key]['create_user_name']=$userModel->getUserInfoByID($value['create_user_id'])['name'];
        }
        return $result;
    }
    /**
     * 查看当前客户确认的房产和贷款金额
     * @param $customer_id
     * @return bool
     */
    public function getUseful($customer_id){
        $where['customer_id'] = $customer_id;//这个客户
        $communicationLogInfo=M('customer_useful')->where($where)->find();
        return $communicationLogInfo;
    }

    /**
     * 查看当前客户的房产和贷款金额是否被确认
     * @param $customer_id
     * @return bool
     */
    public function hasConfirmed($customer_id){
        $where['customer_id'] = $customer_id;//这个客户
        $communicationLogInfo=M('customer_useful')->where($where)->count();
        //有说明被确认
        if($communicationLogInfo){
            return true;
        }else{
            return false;
        }
    }

    function addOrSave($post=array()){
        if(in_array($post['house_status'],array('有','无')) && intval($post['customer_id']) >0 && is_numeric($post['loan_amount'])){
            $data['house_status'] = $post['house_status'];
            $data['customer_id']  = intval($post['customer_id']);
            $data['loan_amount']  = intval($post['loan_amount']);
            $data['useful_time']  = time();
            $data['create_user_id']  = $post['create_user_id'] ? $post['create_user_id'] : session('user_info.user_id');
            if(!$this->hasConfirmed(intval($post['customer_id']))){
                M('customer_useful')->add($data);
            }
        }
        if($post['id']){
            return $this->save($post)?true:false;
        }else{
            return $this->add($post)?true:false;
        }
    }
}