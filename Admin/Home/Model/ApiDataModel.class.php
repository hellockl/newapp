<?php

namespace Home\Model;

use Think\Model;

/**
 * Created by PhpStorm.
 * Date: 2016/8/8
 * Time: 11:30
 */
class ApiDataModel extends Model
{

    private $priefix = '';
    public function __construct(){
        parent::__construct();
        $this->priefix = C("DB_PREFIX");
    }
    /**获取报表
     * @param $time
     * @return bool
     */
    public function getRes($time){
        if(!$time){ return false;}
        $where['create_time'] = array("eq",$time);
        $info=M("api_data")->where($where)->field('reg_count,repeat_count,useful_count')->order("id desc")->find();
        if(!$info){return array("reg_count"=>"0","repeat_count"=>"0","useful_count"=>"0");}
        return $info;
    }

    /**获取时间范围内的有效客户数
     * @param $customer_source 客户来源
     * @param $utm_source  渠道来源
     * @param $start 开始时间
     * @param $end 结束时间
     * @return bool
     */
    public function usefulcount($customer_source,$utm_source,$start,$end){
        $where['b.customer_source'] =array("eq",$customer_source);
        $where['b.utm_source'] =array("eq",$utm_source);
        $where['a.loan_amount'] = array("egt","100000");
        $where['a.useful_time'] = array("between",array($start,$end));

        $info=M("customer_useful")->alias("a")->where($where)->field('count(a.customer_id) as num')
            ->join($this->priefix."customer_sale b on a.customer_id=b.customer_id")
            ->find();
        if(!$info){return false;}
        return $info['num'];
    }

    /**获取时间范围内的注册客户数
     * @param $customer_source 客户来源
     * @param $utm_source  渠道来源
     * @param $start 开始时间
     * @param $end 结束时间
     * @return bool
     */
    public function regcount($customer_source,$utm_source,$start,$end){
        $where['customer_source'] =array("eq",$customer_source);
        $where['utm_source'] =array("eq",$utm_source);
        $where['create_time'] = array("between",array($start,$end));
        $info=M("customer_sale")->where($where)->field('count(customer_id) as num')->find();
        if(!$info){return false;}
        return $info['num'];
    }

    /**插入到数据库中
     * @param $data
     * @return bool
     */
    public function insert($data){
        $info = M("api_data")->add($data);
        if(!$info){return false;}
        return $info;
    }

}