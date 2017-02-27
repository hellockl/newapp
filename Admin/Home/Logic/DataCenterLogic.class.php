<?php
/**
 * Created by PhpStorm.
 * User: Bao
 * Date: 2016/12/1
 * Time: 16:28
 */

namespace Home\Logic;
class DataCenterLogic{
    /**
     * 报表report_sale_task
     * 中间数据组装
     * @return array
     */
    public function count($user_id,$start_time,$end_time)
    {
        $allocate = D("DataCenter")->allocate($user_id,$start_time,$end_time);
        $communication = D("DataCenter")->communication($user_id,$start_time,$end_time);
        $result = array(
            'assigns'=> 0,
            'get_customers'=>0,
            'communication_count'=>0,
            'communication_customers'=>0,
            'assign_and_communication_customers'=>0,
            'get_and_communication_customers'=> 0,
        );
        foreach($allocate as $item){//分配
            $allocate_res['fenpeishu'][] = $item['fenpeishu'];
            $allocate_res['lingqushu'][] = $item['lingqushu'];
            if($item['fenpeishu'] == 1){
                $allocate_wait[$item['customer_id']]['fenpeishu']= 1;//该客户分配
            }
            if($item['lingqushu'] == 1){
                $allocate_wait[$item['customer_id']]['lingqushu'] = 1;//该客户领取
            }
            $result['assigns'] = array_sum($allocate_res['fenpeishu']);//分配客户数
            $result['get_customers'] = array_sum( $allocate_res['lingqushu']);//领取客户数
        }

        foreach($communication as $item){//沟通
            if($allocate_wait[$item['customer_id']] ){
                $allocate_wait[$item['customer_id']]['goutong'] = 1;
            }
            $communication_res['goutongshu'][] = $item['goutongshu'];
            $result['communication_count'] = array_sum($communication_res['goutongshu']);//沟通次数
            $result['communication_customers'] = count($communication_res['goutongshu']);//沟通客户数次数

        }
        foreach($allocate_wait as $item){
            if(isset($item['goutong']) && isset($item['fenpeishu'])){//分配沟通客户数
                $res['assign_and_communication_customers'][] = $item;
                $result['assign_and_communication_customers'] = count($res['assign_and_communication_customers']);
            }
            if(isset($item['goutong']) && isset($item['lingqushu'])){//领取沟通客户数
                $res['get_and_communication_customers'][] = $item['customer_id'];
                $result['get_and_communication_customers'] = count($res['get_and_communication_customers']);
            }
        }
        return $result;
    }
}
