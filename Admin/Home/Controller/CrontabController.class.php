<?php
/**
 * Created by PhpStorm.
 * User: wwy
 * Date: 2016/8/8
 * Time: 10:31
 */
namespace Home\Controller;
use Think\Controller;
class CrontabController extends Controller {

    /**
     * 客户清洗
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     */
    public function autoClean(){
        if(!isset($_SERVER['argv'])){
            return false;
        }
        set_time_limit(0);
        return D("CustomerSale")->autoClean();
    }

    public function showCleanData(){
        $where['owner_user_id'] = array("neq",0);
        $where['customer_type'] = array("eq",'意向客户');
        $where['retain'] = array("eq", 0);
        $data = M('customer_sale')->where($where)->getField("customer_id",true);//查询未保留的意向客户
        //dump(date("w"));
        echo count($data);exit;
    }

    /**定时脚本  每晚21点30执行
     *
     */

    public function crontab51xin(){
        if(!isset($_SERVER['argv'])){
            return false;
        }
        set_time_limit(0);
        $customer_source = 'wangluoyingxiao';
        $utm_source = '51xinyongka';
        $end =  strtotime("21:30:00");
        $start = $end-86400;
        $post['customer_source'] = $customer_source;
        $post['utm_source'] = $utm_source;
        $post['startime'] = date("Y-m-d H:i:s",$start);
        $post['endtime'] = date("Y-m-d H:i:s",$end);
        $json = post_url(C('OCDC_REPEAT'), $post);
        $res = json_decode($json,true);
        $m = D("Home/Api_data");
        $data['customer_source'] = $customer_source;
        $data['utm_source'] = $utm_source;
        $data['useful_count'] = $m->usefulcount($customer_source,$utm_source,$start,$end);
        $data['reg_count'] = $m->regcount($customer_source,$utm_source,$start,$end);
        $data['create_time'] = strtotime(date("Y-m-d"));
        $data['repeat_count'] =$res['retData'];
        $res = $m->insert($data);
        if($res){
            echo "ok";
        }else{
            echo "false";
        }
    }


}