<?php

namespace Home\Model;

use Think\Model;
use Log\Model\LogModel as Log;
use Api\Controller\MessageController as Message;
use Home\Model\UserModel as User;

/**
 * Created by PhpStorm.
 * Date: 2016/8/8
 * Time: 11:30
 */
class CustomerSaleModel extends Model
{

    /**
     * @var array   定义写入规则
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     */
    protected $_validate = array(
//        array('telephonenumber','/[0-9]{11}$/','您的手机号码有误！'),  //
    );
    
    /**
     * 添加客户
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $data
     * @return bool
     */
    public function addData($data=array()){
        if(!empty($data)){
            $data['telephonenumber'] = phone_encode($data['telephonenumber']);
            M("customer_sale_push_log")->add($data);//记录日志
            $where = array("telephonenumber"=>$data['telephonenumber']);
            if($customer_info = M("customer_sale")->where($where)->find()){
                if(M("customer_sale")->where($where)->save(array("update_time"=>time())) !== false){
                    $Log = new Log();
                    $Log->addCustomerLog('添加客户,手机号码已存在',$customer_info['customer_id']);
                    return -1;
                }else{
                    return false;
                }
            }else{
                $data['extends'] = serialize($data);
                $data['first_fenpei_time'] = time();
//                $customer_id = M("customer_sale")->add($data);
//                $Log = new Log();
//                $Log->addCustomerLog('添加客户',$customer_id);
                $customer_id = $this->autoAllocate($data,C("SERVICECITY"));
                return $customer_id;
            }
        }else{
            return false;
        }
    }

    /**
     * 添加多条客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $data
     * @param array $servicedata
     * @return array|bool
     */
    public function addAllData($data=array(),$servicedata=array()){
        if(empty($data) && empty($servicedata)){return false;}
        M("customer_sale_push_log")->addAll($data);//记录日志
        $success = array();
        $fail = array();
        if(!empty($data)){
            $time = time();
            foreach($data as $k=>$v){  //五大城市的数据
                $v['telephonenumber'] = phone_encode($v['telephonenumber']);
                $customer_info = M("customer_sale")->where(array("telephonenumber"=>$v['telephonenumber']))->find();
                if($customer_info){
                    $v['update_time'] = $time;
                    $res = M("customer_sale")->where(array("telephonenumber"=>$v['telephonenumber']))->save(array("update_time"=>$v['update_time']));
                    if($res !== false){
                        $Log = new Log();
                        $Log->addCustomerLog('添加客户,手机号码已存在',$customer_info['customer_id']);
                        $success[] = $v['id'];
                    }else{
                        $fail[] = $v['id'];
                    }
                }else{
                    $v['owner_user_id'] = 0;
                    $v['first_fenpei_time'] = $time;
                    if($this->autoAllocate($v,C("SALECITY"))){
                        $success[] = $v['id'];
                    }else{
                        $fail[] = $v['id'];
                    }
                }
            }
            $callback = array();
            $callback['success'] = join(",",$success);
            $callback['fail'] = join(",",$fail);
            post_url(C("OCDC_CALLBACK_URl"),$callback);//将业务员的数据回调给ocdc

        }
        if(!empty($servicedata)){//非五大城市的数据
            $arr['data'] = $servicedata;
            $json = post_url(C("API_URl"),$arr);
            $res = json_decode($json,true);
            if($res['errNum'] == 0){
                $callback = array();
                $callback['success'] = join(",",$res["retData"]["success"]);
                $callback['fail'] = join(",",$res["retData"]["fail"]);
                post_url(C("OCDC_CALLBACK_URl"),$callback);//将客服的数据回调给ocdc
            }else{
                file_put_contents("service_error".date("Y-m-d").".log",print_r($json,true));
            }
        }
        return ;
    }

    /**
     * 修改时间
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $phone
     * @return bool
     */
    public function updateDataTime($phone){
        if($phone){
            $where = array("telephonenumber"=>$phone);
            $customer_info = M("customer_sale")->where($where)->find();
            if($customer_info){
                $Log = new Log();
                $Log->addCustomerLog('修改时间',$customer_info['customer_id']);
            }
            return M("customer_sale")->where($where)->save(array("update_time"=>time()));
        }
        return false;
    }

    /**
     * 查找电话
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $phone
     * @return bool
     */
    public function findTel($phone){
        if($phone == false){
            return false;
        }
        $where = array("telephonenumber"=>phone_encode($phone));
        return M("customer_sale")->where($where)->count();
    }

    /**
     * 更新客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $set
     * 编辑$set['customer_id'] 必填
     * 新建$set['user_Id'] 必填
     * @return bool
     *更新客户信息
     */
    public function updateCustomer($set)
    {
        $base = D('Customer/CustomerBase');
        if ($this->create() && $set) {
            if ($set['address']) {
                $this->customer_address_sale = $set['address']["province"] . "\n" . $set['address']["city"] . "\n" . $set['address']['area'] . "\n" . $set['address']['street'];
                $this->house_location_sale = $set['address']['provinces'] . "\n" . $set['address']['citys'] . "\n" . $set['address']['areas'] . "\n" . $set['address']['streets'];
            }
            $this->owner_user_id_sale = $set['user_id'];//当前负责人
            $this->update_time_sale = time();
            $log = new Log();
            $this->startTrans(); //事务开始
            if ($set['customer_id']) {//编辑客户
                if (FALSE !== $this->where(array('customer_id' => $set['customer_id']))->save() && $base->setCustomer($set['customer_id']) !== FALSE) {
                    $log->addLog($set['descreption'] . '客户成功！ | ');
                    $log->addCustomerLog($set['descreption'] . '客户成功！', $set['customer_id'], $set['user_id']);
                    $this->commit();
                    return true;
                }
                $this->rollback();    // 事务回滚
                return false;
            } else {
                $this->creater_user_id_sale = $set['user_id'];
                $base_result = $base->setCustomer();
                if (FALSE !== $base_result) {
                    $this->customer_id = $base_result;
                    if (FALSE !== $this->add()) {
                        if ($log->addLog($set['descreption'] . '客户成功！ | ') !== false) {
                            if ($log->addCustomerLog($set['descreption'] . '客户成功！', $base_result, $set['user_id'])) {
                                $this->commit();
                                return true;
                            }
                        }
                    }
                }
                $this->rollback();      // 事务回滚
                return false;
            }
        }
        return false;
    }


    /**
     * 添加或者修改数据
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $post
     * @return bool
     */
    function addOrSave($post=array(),$operation=''){
        $post['update_time']=time();
        if($post['owner_user_id']){
            $arr = array("user_ids"=>$post['owner_user_id']);
            $user_names = post_url(C('BASE_getUserNames'),$arr);
            $user_names = json_decode($user_names,true);
            $post['owner_user_name'] = $user_names['retData'][$post['owner_user_id']];
        }
        //如果存在手机号,加密手机号
        if($post['telephonenumber']){
            $post['telephonenumber'] = phone_encode($post['telephonenumber']);
        }
        //如果存在手机号,加密手机号

        if($operation!=''){
            $operation=$operation;
        }else{
            $operation=$post['customer_id']?'修改客户':'添加客户';
        }
        if($post['customer_id']){
            if($this->save($post)){
                $log = new Log();
                $log->addCustomerLog($operation, $post['customer_id']);
                return true;
            }else{
                return false;
            }
        }else{
            $post['first_fenpei_time'] = time();
            if($customer_id = $this->add($post)){
                $log = new Log();
                $log->addCustomerLog($operation, $customer_id);
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 查找客户信息(并且解密手机号)
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $where
     * @return bool|mixed
     */
    public function findCustomerByWhere($where = array())
    {
        $result = $this->where($where)->find();
        if (!empty($result)) {
            $result['telephonenumber']=phone_decode($result['telephonenumber']);
            return $result;
        }
        return false;
    }


    /**
     * 查找客户信息
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $where
     * @return bool|mixed
     */
    public function selectCustomerByWhere($where = array())
    {
        $result = $this->where($where)->select();
        if (!empty($result)) {
            return $result;
        }
        return false;
    }


    /**
     * 获取业务员客户列表
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param string $type
     * @param $uids
     * @param array $search
     * @param int $p
     * @param string $city
     * @param int $perpage
     * @param array $status  默认查询有效状态的客户
     * @return array
     */
    public function getList($type = 'self', $uids, $search = array(), $p = 1,$city='', $perpage = 20)
    {
        $perpage = $search['perpage']?$search['perpage']:$perpage;
        $my_user_id=session("user_info.user_id");
        $where = array();
        //有没有查询居间协议查询k
        if ($search['agreement_number']) {
            $agreementInfo=D('Agreement')->where(array('number'=>$search['agreement_number']))->find();
            $where['customer_id'] = array("eq", $agreementInfo['customer_id']);
        }
        if ($type == 'self') {
            if (trim($search['owner_user_name'])) {//如果有负责人搜索
                $user_ids=array_filter(explode(',',$uids.','.$my_user_id));
                $user_id = (new UserModel())->getUserIdByName(trim($search['owner_user_name']),$user_ids);
                if($user_id==""){
                    return array("data" => array(), "page" => '', "oPage"=>'','perpage'=>$perpage);
                }
                $where['owner_user_id'] = $user_id;
            }else{
                $user_ids=array_filter(explode(',',$uids.','.$my_user_id));
                $where['owner_user_id'] = array("in", $user_ids);
            }
        } else {
            $where['owner_user_id'] = array("eq", 0);
            //是否是分公司
            if($type=='local'){
                $where['city'] =session('user_info.data_type')!=4?array("eq", $city):array("in", C("SALECITY"));
            }else{
                $where['city'] =array("not in", C("SALECITY"));
                if (trim($search['city'])){
                    if(in_array(trim($search['city']),C("SALECITY"))){return array();}
                    $where['city'] = array("eq",trim($search['city']));
                }
            }
        }

        if (trim($search['utm_source'])){$where['utm_source'] = array("eq", trim($search['utm_source']));}//来源查询
        if (trim($search['customer_name'])){$where['customer_name'] = array("like", "%" . trim($search['customer_name'])."%");}
        if (trim($search['customer_level'])){$where['customer_level'] = array("eq", trim($search['customer_level']));}
        if (trim($search['customer_classify'])){$where['customer_classify'] = array("eq", trim($search['customer_classify']));}
        if (trim($search['customer_type'])){$where['customer_type'] = array("eq", trim($search['customer_type']));}
        if (trim($search['telephonenumber'])){$where['telephonenumber'] = array("eq", phone_encode(trim($search['telephonenumber'])));}
        if($search['create_date']){
            $where["create_time"]  = array('between',array(splitDate($search['create_date'])[0],splitDate($search['create_date'])[1]));
        }
        //客户来源筛选
        if(trim($search['customer_source'])){$where["customer_source"] = array("eq",trim($search['customer_source']));}

        //看看有没有排序条件
        if($search['orderby']){
            $order=$search['orderby'].' '.$search['pot'];//定义列表排列顺序
        }else{
            $order=$type == 'self'?'receive_time desc':'update_time desc';//定义列表排列顺序
        }
        $Agreements = array();
        $list = $this->where($where)->order($order)->page($p, $perpage)->select();

        $customerids = array_column($list,"customer_id");
        if(!empty($customerids)){
            $where1['customer_id'] = array("in",$customerids);
            $Agreement = M("Agreement")->where($where1)->field("customer_id as aid")->select();
            $Agreements = array_column($Agreement,"aid");
        }
        $userModel=new UserModel();
        foreach($list as $key=>$value){
            $value['telephonenumber']=phone_decode($value['telephonenumber']);
            if($type == 'self'){
                $list[$key]['owner_user_name']=$userModel->getUserInfoByID($value['owner_user_id'])['name'];
                $list[$key]['has_agreement'] = in_array($value['customer_id'],$Agreements)?1:0;
            }
            if($type=='self'){
                if($_SESSION['user_info']['look_phone'] == 2){
                    $list[$key]['telephonenumber'] = $value['telephonenumber'];
                }elseif($_SESSION['user_info']['look_phone'] == 1){
                    $list[$key]['telephonenumber']=$value['owner_user_id']==$my_user_id?$value['telephonenumber']:substr($value['telephonenumber'], 0 , 7)."****";
                }else{
                    $list[$key]['telephonenumber'] = substr($value['telephonenumber'], 0 , 7)."****";
                }
            }else{
                $list[$key]['telephonenumber'] = substr($value['telephonenumber'], 0 , 7)."****";
            }
        }

        $count = $this->where($where)->count();
        $Page = new \Think\Page($count, $perpage);
        return array("data" => $list, "page" => $Page->show(), "oPage"=>$Page,'perpage'=>$perpage);
    }

    /**
     * 领取客户或者保留客户
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $customer_id  客户id
     * @param $owner_id  负责人id
     * @param string $type 类型type  receive 领取    retain 保留 unretain
     * @param string $owner_name
     * @return bool
     */
    public function updateCustomerByType($customer_id, $owner_id, $type = 'receive',$owner_name='')
    {
        if ($customer_id == false || $owner_id == false) {return false;}
        if ($type == false || !in_array($type, array("receive", "retain", "unretain"))) {return false;}
        $customer = $this->where(array("customer_id" => $customer_id))->count();
        if (empty($customer)) {return false;}

        $userinfo=D('Contract')->getUserByWhere(array('user_id'=>$owner_id),2);
        $user=count($userinfo);

        if ($user < 1) {return false;}
        $where['customer_id'] = $customer_id;
        if ($type == 'receive') {
            $data['owner_user_id'] = $owner_id;
            $data['owner_user_name'] = $owner_name;
            $data['receive_time'] = time();//领取时间
        } else if ($type == 'retain') {
            $where['owner_user_id'] = $owner_id;
            $data['retain'] = 1;
        } else {
            $where['owner_user_id'] = $owner_id;
            $data['retain'] = 0;
        }
        if($this->where($where)->save($data) !== false ){
            $log = new Log();
            if ($type == 'receive') {$log_description='领取客户';}
            else if ($type == 'retain') {$log_description='保留客户';}
            else {$log_description='取消保留客户';}
            $log->addCustomerLog($log_description, $customer_id);
            return true;
        }else{
            return false;
        }
        //return $this->where($where)->save($data) !== false?true:false;
    }

    /**
     * 自动清洗客户（清洗负责人id为0）
     * @author <wwy@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return bool
     */
    public function autoClean()
    {
        ini_set ('memory_limit', '1580M');
        $where = array();
        $save = array();
        $where['owner_user_id'] = array("neq",0);
        $where['customer_type'] = array("eq",'意向客户');
        $where['retain'] = array("eq", 0);
        $count =  M('customer_sale')->where($where)->count();
        while($count > 0){
            $data = M('customer_sale')->where($where)->limit(3000)->select();
            if(empty($data)){
                break;
            }
            $save['owner_user_id'] = 0;
            $this->startTrans();
            $update = $this->where($where)->limit(3000)->save($save);//将查询未保留的意向客户的负责人id变成0
            if ($update !== false) {
                $addall = array();
                $autodata = array();
                $time = time();
                foreach ($data as $k=>$v){
                    $addall[$k] = $v;
                    $addall[$k]['log_user_id'] = 0;
                    $addall[$k]['log_create_time'] = $time;
                    $addall[$k]['log_description'] = '自动清洗';

                    $autodata[$k]['operation'] = '客户清洗';
                    $autodata[$k]['customer_id'] = $v['customer_id'];
                    $autodata[$k]['customer_name'] = $v['customer_name'];
                    $autodata[$k]['old_owner_id'] = $v['owner_user_id'];
                    $autodata[$k]['new_owner_id'] = 0;
                    $autodata[$k]['create_user_id'] = 0;
                    $autodata[$k]['create_user_name'] = '房金所系统';
                    $autodata[$k]['create_time'] = $time;
                }
                M('customer_log')->startTrans();
                $mlog = M('customer_log')->addAll($addall);
                if($mlog){
                    $res = M('allocate_log')->addAll($autodata);
                    if($res){
                        $this->commit();
                        M('customer_log')->commit();
                    }else{
                        $this->rollback();
                        M('customer_log')->rollback();
                    }
                }else{
                    $this->rollback();
                }
            } else {
                $this->rollback();
            }
            $count =  M('customer_sale')->where($where)->count();
            echo $count."\n";
        }
        echo date('Y-m-d H:i:s') ." | 自动清洗客户（清洗负责人id为0）\n";
    }

    /**
     * 自动分配
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return bool
     */
    public function autoAllocate($data = array(),$citys){
        if(empty($data)){ return false; }
        if(in_array($data['city'],$citys)){
            $data['owner_user_id'] = RedisModel::instance()->getFirstUser('listAllocate'.$data['city']);
        }else{
            $data['owner_user_id'] = 0;
        }
        $owner_user_id = $data['owner_user_id'];
        $customer_id = $this->add($data);
        if($customer_id){
            if(in_array($data['city'],$citys)){
                RedisModel::instance()->updateUserList('listAllocate'.$data['city']);
            }
            $this->allocateLog($customer_id,$owner_user_id,1);
            if($owner_user_id != 0){
                $MSG = new Message();
                $User = new User();
                $user_info = $User->getUserInfoByID($owner_user_id);
                $content = '房金所系统将新客户分配给了你,客户电话:'.phone_decode($data['telephonenumber'],1).',请注意跟进';
                if($user_info['telephone'] && $content){
                    $phone['user_telephone'] = $user_info['telephone'];//业务员手机号
                    $phone['content'] = $content;//短信内容
                    $phone['create_time'] = time();
                    M("phone_log")->add($phone);
                }
                $MSG->send($user_info['telephone'],$content);
            }
            return $customer_id;
        }else{
            return false;
        }
    }

    //批量分配
    public function allocateAll($post = array()){
        $customer_id = array_filter(explode(',',$post['customer_ids']));
        if($customer_id){
            $owner_user_id = intval($post['owner_user_id']);
            $data['owner_user_id'] = $owner_user_id;
            $data['owner_user_name'] = trim($post['owner_user_name']);
            $data['update_time'] = time();
            $data['receive_time'] = time();
            $customer_num = 0;
            foreach ($customer_id as $k=>$v){
                M('allocateLog')->startTrans();
                $this->allocateLog($v,$owner_user_id,2);
                if($this->where('customer_id = %d',$v)->save($data)){
                    M('allocateLog')->commit();
                    $customer_num ++;
                }else{
                    M('allocateLog')->rollback();
                }
            }
            $MSG = new Message();
            $User = new User();
            $user_info = $User->getUserInfoByID($owner_user_id);
            $content = $_SESSION['user_info']['name'].'分配给了你'.$customer_num.'个客户,请注意跟进。';
            if($user_info['telephone'] && $content){
                $phone['user_telephone'] = $user_info['telephone'];//业务员手机号
                $phone['content'] = $content;//短信内容
                $phone['create_time'] = time();
                M("phone_log")->add($phone);
            }
            $MSG->send($user_info['telephone'],$content);
            $return['errNum'] = 0;
            $return['errMsg'] = '批量分配成功';
        }else {
            $return['errNum'] = 1;
            $return['errMsg'] = '批量分配失败';
        }
        return $return;
    }

    /**
     * 添加分配日志
     * @param int $customer_id
     * @param int $new_owner_id
     * @param int $type 1是自动分配  2手动分配
     * @return bool
     */
    public function allocateLog($customer_id = 0,$new_owner_id = 0,$type=1){
        if($customer_id == 0){
            return false;
        }
        $customer_info = $this->find($customer_id);
        if($type==1){
            $data['old_owner_id'] = 0;
            $data['operation'] = '自动分配';
        }else{
            $data['old_owner_id'] = $customer_info['owner_user_id'];
            $data['operation'] = '手动分配';
        }
        $data['customer_id'] = $customer_id;
        $data['customer_name'] = $customer_info['customer_name'];
        $data['new_owner_id'] = $new_owner_id;
        $data['create_user_id'] = session('user_info.user_id') ? session('user_info.user_id') : 75;
        $data['create_user_name'] = session('user_info.name') ? session('user_info.name') : '房金所系统';
        $data['create_time'] = time();

        $description=$type==1?'自动分配':'手动分配';
        if(M('allocateLog')->add($data)){
            $Log = new Log();
            if($Log->addCustomerLog($description,$customer_id)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 用户有没有权限查看客户
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $customerInfo
     * @param $user_id
     * @return bool|string
     * 默认是自己的有权限
     */
    public function hasPowerLookCustomer($customerInfo,$user_id){
        //查询有没有60个客户
        $hastime=$customerInfo['phone_view_time']+C('LOOK_RETAIN_TIME')*60-time();
        //先查看自己有没有权限,固定的时间内自己有没有查过,有查看过就可以操作
        if(!(($customerInfo['phone_view_uid']==$user_id)&&($hastime>0))){
            //客户查看次数上限
            if($customerInfo['phone_view_count']>=C('CUSTOMER_UP_LOOK_NUM')){return '这个客户查看上限,不能被领取';}
            //被领取的客户不能被别人查看
            if($customerInfo['owner_user_id']>0){return '这个客户已被人领取';}
            //客户是不是被别人查看,还在被锁定中
            if($hastime>0){return '这个客户锁定中,请等待'.$hastime.'秒';}
            return 1;
        }else{
            return 2;
        }
    }

    /**
     * 客户附件信息
     * @param   int  $customer_id
     * @return  bool
     */
    public function customerDocument($customer_id = 0){
        if(!$customer_id){ return false; }
        $list = D("DocumentContractView")->where("customer_id=%d and rc_document_status=1",$customer_id)->select();
        return $list;
    }

    public function customerSourceByGroup(){
        return $this->group('customer_source desc')->field('customer_source')->select();
    }


    /**
     * 添加居间协议的时候更改用户的状态
     * @param $customer_id
     * @return bool
     */
    public function updataCustomerType($customer_id){
        $result = $this->where(array('customer_id'=>$customer_id,'customer_type'=>'意向客户'))->save(array('customer_type'=>'协议客户'));
        if($result !== false) {
            $Log = new Log();
            $Log->addCustomerLog('签居间协议',$customer_id);
        }else{
            return false;
        }
        return true;
    }


    /**
     * 添加居间协议的事物
     * @param $agreementInfo
     * @return bool|string
     */
    public function addAgreement($agreementInfo){
//        $this->startTrans();
        //开启事务,防止协议没成功写入,而客户状态变更了
        //如果客户是意向客户,更改客户的状态为协议客户
        $updataToXieyi=$this->updataCustomerType($agreementInfo['customer_id']);
        if ($updataToXieyi===true){
            $return = (new AgreementModel())->agreementAddOrSave(I('post.'));
            if($return===true){
//                $this->commit();
                return true;
            }else{
//                $this->rollback();
                return $return;
            }
        }else{
            return '添加协议失败';
        }
    }
}