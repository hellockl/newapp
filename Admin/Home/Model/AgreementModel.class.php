<?php
/**
 * Created by PhpStorm.
 * User: dingwenzi
 * Date: 2016/7/12
 * Time: 14:04
 */

namespace Home\Model;
use Think\Model;

class AgreementModel extends Model
{
    /**
     * @var array   定义写入规则
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     */
    protected $_validate = array(
        array('number','/[A-Z][0-9]{1,10}$/','居间协议编号格式有误！'),  //
        array('plan_money','/^[0-9]+(.[0-9]{1,2})?$/','拟借款金额必须是有效的值,可以精确到小数点两位'),  //
    );
    /**
     * 客户是否有未完成的居间协议
     * @param $customer_id  客户id
     * @return bool
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     */
    public function customerHasUnactionAgreement($customer_id){
        $info=$this->where(array('customer_id'=>$customer_id,'status'=>0))->select();
        return $info?false:true;
    }
    /**
     * 添加居间协议
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param $post  这里面有两组数组:第一组是写入居间协议,第二组是写入回款记录定金
     * @return bool
     */
    public function agreementAddOrSave($post){
        if(isset($post['agreement']['agreement_id'])){
            //先查看是不是之前的居间协议号
            $sameAgreement=$this->where(array('agreement_id'=>$post['agreement']['agreement_id'],'number'=>$post['agreement']['number']))->count();
            if($sameAgreement==0){
                if($this->where(array('number'=>$post['agreement']['number']))->find()){return '您填写的协议名称已经存在';}
            }
        }else{
            if($this->where(array('number'=>$post['agreement']['number']))->find()){return '您协议名称重复';}
        }

        $post['agreement']['create_user_id']=session("user_info.user_id");
        $post['agreement']['create_time']=time();

        if($post['agreement']['commission']&&!preg_match('/^[0-9]+(.[0-9]{1,2})?$/',$post['agreement']['commission'])){
            return '佣金必须是有效的值,可以精确到小数点两位';
        }
        $this->startTrans();//开启事务,防止协议写入,而定金没有写入

        if($this->create($post['agreement'])&&($agreement_id = $this->addOrSave($post['agreement']))){//添加协议
            if(($post['receivables']['deposit']!='')&&($post['is_deposit'])){//判断用户有没有打开收定金
                if($post['agreement']['agreement_id']){//如果是修改协议,查询有没有收定金
                    $where=array('agreement_id'=>$post['agreement']['agreement_id'],'commission'=>0,'rate'=>0,
                        'principal'=>'0','return_fee'=>0,'packing'=>0,'channel_money'=>0,'other_fee'=>0);
                    $infoReceivables=M('receivables')->where($where)->find();

                    if($infoReceivables)                //有收定金的记录
                        $post['receivables']['receivables_id']=$infoReceivables['receivables_id'];
                    $post['receivables']['agreement_id']=$post['agreement']['agreement_id'];
                }else{                                  //如果是添加协议
                    $post['receivables']['agreement_id']=$agreement_id;
                }

                if ((D("Receivables")->receivablesAdd($post['receivables']))===true){
                    $this->commit();return true;
                }else{
                    $this->rollback();return D("Receivables")->getError();
                }
            }else{
                $this->commit();return true;
            }
        }else{
            return $this->getError();
        }
    }
    /**
     * 添加或者修改数据
     * @param array $post
     * @return bool
     */
    function addOrSave($post=array()){
        //加密手机号
        if($post['telephone']){$post['telephone']=phone_encode($post['telephone']);}

        $return=$post['agreement_id']?$this->save($post):$this->add($post);
        return $return?$return:false;
    }
    /**
     * 获取居间协议的列表分页
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $get
     * @param $users
     * @return mixed
     */
    public function agreementList($get = array(),$users){
        if($get['telephonenumber']){
            $customer_id = M('customer_sale')->where(array('telephonenumber'=>$get['telephonenumber']))->getField('customer_id');;
            $where["customer_id"]  = $customer_id;
        }
        if($get['agreement_date']){
            $where["create_time"]  = array('between',array(splitDate($get['agreement_date'])[0],splitDate($get['agreement_date'])[1]));
        }
        $status = array(-1=>'无效',0=>'处理中',1=>'成功',2=>'失败');
        $where["number"] = array('like','%'.$get["agreement_number"].'%');          //协议编号相似

        if($get['status']){//居间协议状态  默认状态为处理中
            $where['status'] = array('eq',$get['status']);
        }elseif($get['status']===''){
        }else{
            $where['status'] = array('eq',0);
        }

        $user_arr= explode(',',$users);
        if (trim($get['owner_user_name'])) {//如果有负责人搜索
            $user_arr=(new UserModel())->getUserIdByName(trim($get['owner_user_name']),$user_arr);
            if($user_arr==""){
                return array("0" => array(), "0" => '');
            }
            $where['create_user_id'] = array("in", $user_arr);
        }else{
            $where['create_user_id'] = array("in", $user_arr);
        }

        $count = $this->where($where)->count();
        $Page = new \Think\Page($count,10);
        $p = $get['p'] ? $get['p'] : 1 ;                                             //获得页码
        $list = $this->where($where)->Page($p,10)->order('agreement_id desc')->select();
        $return = array();
        if(!empty($list)){
            $userModel=new UserModel();
            $customerids=array_column($list,'customer_id');
            $agreementids=array_column($list,'agreement_id');

            //批量获取客户的名字
            $where1['customer_id'] = array("in",$customerids);
            $customerNames = M('customer_sale')->where($where1)->field("customer_id,customer_name")->select();
            $customerNames_arr=array();
            foreach($customerNames as $k=>$v){$customerNames_arr[$v['customer_id']]=$v['customer_name'];}

            //批量获取合同的id
            $where2['agreement_id'] = array("in",$agreementids);
            $agreements = M('contract')->where($where2)->field("agreement_id,contract_id")->select();
            $agreement_arr=array();
            foreach($agreements as $k=>$v){$agreement_arr[$v['agreement_id']]=$v['contract_id'];}

            if($list){
                foreach ($list as $k=>$v){
                    $list[$k]['status_name'] = $status[$v['status']];
                    $list[$k]['create_data'] = $v['create_time'] ? date('Y-m-d',$v['create_time']) : '';
                    $list[$k]["costomer_name"]=$customerNames_arr[$v['customer_id']];

                    $userinfo=$userModel->getUserInfoByID($v['create_user_id']);
                    $list[$k]['user_name']=$userinfo['name'];

                    $list[$k]["contract_id"]=$agreement_arr[$v['agreement_id']];
                }
            }
            $return[0] = $list;
            $return[1] = $Page->show();
        }

        return $return;
    }
    /**
     * 通过客户的id获取协议列表
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $get
     * @return mixed
     */
    public function getAgreeByCustomerId($get = array()){
        $where["customer_id"]  = array('eq',$get['customer_id']);
        $where["status"]  = array('neq',-1);
        $status = array('处理中','成功','失败');

        $list = $this->where($where)->order('agreement_id desc')->select();
        if($list){
            $userModel=new UserModel();
            foreach ($list as $k=>$v){
                $list[$k]['status_name'] = $status[$v['status']];
                $list[$k]['create_data'] = $v['create_time'] ? date('Y-m-d',$v['create_time']) : '';
                $list[$k]["costomer_name"]=M('customer_sale')->where('customer_id = %d',$v['customer_id'])->getField('customer_name');

                $userinfo=$userModel->getUserInfoByID($v['create_user_id']);
                $list[$k]["user_name"]=$userinfo['name'];
                $list[$k]["contract_id"]=M('contract')->where('agreement_id = %d',$v['agreement_id'])->getField('contract_id');
            }
        }
        $return[0] = $list;
        return $return;
    }
    /**
     * 根据协议的id获取协议的信息
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param $id 居间协议的id
     * 这里返回的结果,是居间协议的基本信息
     */
    public function getAgreementInfo($id){
        $info["agreement"]=$this->where("agreement_id=%d",$id)->find();
        //解密手机号
        $info["agreement"]['telephone']=phone_decode($info["agreement"]['telephone']);
        //下面查询定金的信息
        $where=array('agreement_id'=>$id,'commission'=>0,'rate'=>0,'principal'=>'0','return_fee'=>0,'packing'=>0,'channel_money'=>0,'other_fee'=>0);
        $info["receivables"]=M('receivables')->where($where)->find();
        if($info["receivables"]){
            $info["receivables"]["pay_date"]=date('Y-m-d',$info["receivables"]["pay_time"]);
        }
        return $info;
    }
    /**
     * 通过条件查找
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $where
     * @return bool|mixed
     */
    public function findByWhere($where = array())
    {
        $result = $this->where($where)->find();
        if (!empty($result)) {
            //解密手机号
            $result['telephone']=phone_decode($result['telephone']);
            return $result;
        }
        return false;
    }


    /**
     * 通过居间协议信息修改居间协议为特定的状态
     * @param $agreementInfo
     * @param $status
     * @return bool
     */
    public function updataAgreementStatus($agreementInfo,$status){
        if($agreementInfo['status']!=$status){
            if($agreementInfo['agreement_id'] && M('Agreement')->where(array('agreement_id'=>$agreementInfo['agreement_id']))->save(array('status'=>$status)) !== false ){
                return true;
            }else{
                return false;
            }
        }
        return true;
    }


    /**
     * 通过多条居间协议数组查找客户信息
     * @param $agreementInfos_arr
     * @return array   返回的是以居间协议id为主键的数组
     */
    public function getCustomerInfoByAgreements($agreementInfos_arr){
        //取出所有的客户id
        $customerIds=array_column($agreementInfos_arr,'customer_id');
        //取出客户的信息,并且以客户的id为主键
        $customerInfosKeyIsCustomerId=array();
        if(!empty($customerIds)){
            $customerWhere['customer_id']=array('in',$customerIds);
            $customerInfos=(new CustomerSaleModel())->selectCustomerByWhere($customerWhere);
            foreach($customerInfos as $v){
                $customerInfosKeyIsCustomerId[$v['customer_id']]=$v;
            }
        }
        //遍历居间协议信息,以居间协议id为主键关联客户信息
        $agreementKeyToCustomer=array();
        foreach($agreementInfos_arr as $v){
            $oneAgreement['customer_name']=$customerInfosKeyIsCustomerId[$v['customer_id']]['customer_name'];
            $agreementKeyToCustomer[$v['agreement_id']]=$oneAgreement;
        }
        return $agreementKeyToCustomer;
    }
}