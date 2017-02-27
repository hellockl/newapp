<?php
/*
 *  数据源
 * SELECT *
 * 查询沟通日志 分配领取日志 有效客户日志 用户信息 总共3次查询
 *
 */
namespace Home\Model;
use Think\Model;
class DataCenterModel extends Model {
    protected $tableName = 'allocate_log';
    /**
     * 查询自动分配数 ,领取数
     * @return array(
     *  array("new_owner_id"=>"","fenpeishu"=>"")
     * )
     */
    public function allocate($user_id,$start_time,$end_time){
        $sql = "SELECT new_owner_id,customer_id,
                COUNT(CASE WHEN operation = '自动分配' THEN 1 END)as fenpeishu,
                COUNT(CASE WHEN operation = '领取客户' THEN 1 END)as lingqushu
                from ". $this->tablePrefix ."allocate_log
        where create_time between '".$start_time."' and '".$end_time."' and new_owner_id ='".$user_id."'
        group by customer_id";
        $result = $this->query($sql);
        return $result;
    }

    /**
     * 查询 沟通次数
     * 沟通客户数需要分别统计
     * @return array(
     *  array("create_user_id"=>"1","goutongshu"=>"1")
     * array("create_user_id"=>"1","goutongshu"=>"2")
     * array("create_user_id"=>"1","goutongshu"=>"3")
     * 沟通客户数3次 沟通次数6次
     * )
     */
    public function communication($user_id,$start_time,$end_time){
        $sql = "Select a.create_user_id,a.customer_id,COUNT(*) as goutongshu
                from ". $this->tablePrefix ."communication_log a
        where a.create_time between '".$start_time."' and '".$end_time."' and a.create_user_id ='".$user_id ."'
        group by a.customer_id";
        $result = $this->query($sql);
        return $result;
    }
}
?>