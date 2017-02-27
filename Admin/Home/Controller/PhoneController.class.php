<?php
/**
 * Created by PhpStorm.
 * User: Bao
 * Date: 2016/7/27
 * Time: 13:06
 */

namespace Home\Controller;

class PhoneController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 检查电话号码
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param $tel 电话号码
     * @param $customer_id 客户id 存在客户id
     * @param return 返回  如果该号码存在返回true
     * 查找该号码是否存在
     */
    public function checkTelephone($tel,$customer_id=false){
        $res = array();
        $res['status'] = false;
        if($tel == false){$res['status'] = false;}
        if($customer_id){
            $where['customer_id'] = array("neq",$customer_id);
            $where['telephonenumber'] = array("eq",phone_encode($tel));
        }else{
            $where['telephonenumber'] = array("eq",phone_encode($tel));
        }
        $data = D("CustomerSale")->where($where)->find();
        if(!empty($data)){
            $res['status'] = true;
            $res['data'] = $data;
        }
        echo json_encode($res);
    }

    /**
     * 根据客户检查号码
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param $tel 电话号码
     * @param $customer_id 客户id 存在客户id
     * @param return 返回  如果该号码存在返回true
     * 查找该号码是否存在
     */
    public function checkTelephoneBySale($tel,$customer_id=false){
        $res = array();
        $res['status'] = false;
        if($tel == false){$res['status'] = false;}
        if($customer_id){
            $where['customer_id'] = array("neq",$customer_id);
            $where['telephonenumber'] = array("eq",phone_encode($tel));
        }else{
            $where['telephonenumber'] = array("eq",phone_encode($tel));
        }
        $data = D("Sale/CustomerSale")->findCustomerByWhere($where,true);//true仅仅查找电话号码
        if(!empty($data)){
            $res['status'] = true;
            $res['data'] = $data;
        }
        echo json_encode($res);
    }
}