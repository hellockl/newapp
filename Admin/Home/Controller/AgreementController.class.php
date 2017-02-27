<?php
/**
 * Created by PhpStorm.
 * User: dingwenzai
 * Date: 2016/8/5
 * Time: 17:20
 */

namespace Home\Controller;
use Home\Controller\BaseController;
use Home\Model\AgreementModel;
use Home\Model\CustomerSaleModel;

class AgreementController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 居间协议列表
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     */
    public function index()
    {
        $this->telephonenumber = I('get.telephonenumber', '');
        $this->display();
    }

    /**
     * 居间协议列表获取的表格
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param get方式-查询条件
     */
    public function table()
    {
        $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"));
        $users=implode(',',$users_arr);

        $result = D('Agreement')->agreementList(I('get.'),$users);
        $this->assign('brand_list', $result[0]);
        $this->assign('pageBar', $result[1]);
        $this->display();
    }
    /**
     * 通过客户id获取协议列表信息
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param get方式获取客户的id
     */
    public function onecustomer(){
        $result = D('Agreement')->getAgreeByCustomerId(I('get.'));
        $this->assign('brand_list', $result[0]);
        $this->display();
    }


    public function updataStatus($id,$toStatus){
        $agreementInfo=(new AgreementModel())->findByWhere(array('agreement_id'=>$id));
        if($agreementInfo['create_user_id']!=$this->user_id){$this->errorjsonReturn('你没有权限');}
        if(in_array($agreementInfo['status'],array(1,2))){$this->errorjsonReturn('协议状态不允许更改');}
        if(!in_array($toStatus,array(-1,0))){$this->errorjsonReturn('您的操作有误');}
        if((new AgreementModel())->addOrSave(array('agreement_id'=>I('get.id'),'status'=>$toStatus))){
            $this->setjsonReturn('操作成功');
        }else{
            $this->errorjsonReturn('操作失败');
        }
    }
    /**
     * 添加居间协议
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param post方式 添加居间协议
     * @return json数据
     * @param get方式 id 根据客户id进入添加协议界面
     */
    public function add()
    {
        if (IS_POST) {
            $agreement=I('post.agreement');
            $customer_id = (int)$agreement['customer_id'];
            if(!$customer_id){$this->errorjsonReturn('没有选择客户！');}
            $customerInfo=D('CustomerSale')->findCustomerByWhere(array('customer_id'=>$customer_id));
            if($customerInfo['owner_user_id']!=$this->user_id){$this->errorjsonReturn('你没有权限处理！');}

            $agreementInfo=I('post.agreement');
            $return=(new CustomerSaleModel())->addAgreement($agreementInfo);
            if($return===true){
                $this->setjsonReturn('添加居间协议成功');
            }else{
                $this->errorjsonReturn($return);
            }
        } else {
            if(I('get.id')){
                $customer_id=I('get.id');
                $customerInfo=D('CustomerSale')->findCustomerByWhere(array('customer_id'=>$customer_id));
                if($customerInfo['owner_user_id']!=$this->user_id){exit('你没有权限处理！');}
                $this->assign('customer',$customerInfo);
            }
            $this->display();
        }
    }

    /**
     * 修改居间协议
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param post方式 修改居间协议
     * @return json数据
     * @param get方式 id 根据居间协议id进入界面
     */
    public function edit()
    {
        if (IS_POST) {
            $return = (new AgreementModel())->agreementAddOrSave(I('post.'));
            $return === true ? $this->setjsonReturn('修改居间协议成功'):$this->errorjsonReturn($return);
        } else {
            $info = D('Agreement')->getAgreementInfo(I('get.id'));
            if($info['agreement']['create_user_id']!=session('user_info.user_id')){echo('您没有该协议的权限');die();}

            $customer = D("CustomerSale")->findCustomerByWhere(array('customer_id' => $info['agreement']['customer_id']));
            $this->assign('customer',$customer);
            $this->assign('info', $info);
            D('Contract')->getAgreementLock(I('get.id'))?$this->assign('lock', 1):$this->assign('lock', 0);
            $this->display();
        }
    }

    /**
     * 查看协议界面
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param post方式 修改居间协议
     * @return json数据
     * @param get方式 id 根据居间协议id进入界面
     */
    public function view(){
        $info = D('Agreement')->getAgreementInfo(I('get.id'));
        $customer = D("CustomerSale")->findCustomerByWhere(array('customer_id' => $info['agreement']['customer_id']));
        $this->assign('customer',$customer);
        $this->assign('info', $info);
        D('Contract')->getAgreementLock(I('get.id'))?$this->assign('lock', 1):$this->assign('lock', 0);
        $this->display();
    }
}