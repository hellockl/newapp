<?php
/**
 * Created by PhpStorm.
 * User: ZengZhiQiang
 * Date: 2016/08/08
 * Time: 14:04
 */

namespace Home\Model;
use Think\Model;
class CheckModel extends Model
{

    /**
     * 审核流程列表
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param  $contract_id
     * @return bool
     */
    public function checkList($contract_id = 0){
        if(!$contract_id){ return false; }
        $check_list = $this->where('contract_id = %d',$contract_id)->select();
        $check_process = C('CHECK_PROCESS');
        if($check_list){
            $userModel=new UserModel();
            foreach ($check_list as $k=>$v){
                $check_list[$k]['check_process'] = $check_process[$v['check_process']];
                $userinfo=$userModel->getUserInfoByID($v['check_user_id']);
                $check_list[$k]['check_user_name']=$userinfo['name'];
            }
        }
        return $check_list;
    }

    /**
     * 根据条件查找审核信息
     * @param array $where
     * @return mixed
     */
    public function selectByWhere($where=array()){
        $check_list = $this->where($where)->select();
        return $check_list;
    }
    /**
     * 查询某个人相关的合同id
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $where
     * @return array|bool
     */
    public function countContractByWhere($where = array())
    {
        $result = $this->where($where)->field('contract_id')->group('contract_id')->select();

        if (!empty($result)) {
            $array=array();
            foreach($result as $value){
                $array[]=$value['contract_id'];
            }
            return $array;
        }
        return false;
    }
    /**
     * 判断当前用户有没有审核的权限
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $post
     * @return bool
     */
    public function check_validate($post=array()){
        $post['contract_id'] = $post['contract_id'] ? $post['contract_id'] : $post['id'];
        $where['status'] = 0;
        $where['is_delete'] = 0;
        $where['contract_id'] = $post['contract_id'];
        $contract_info = M('contract')->where($where)->find();
        if($contract_info){
            return $contract_info['create_user_id']==session('user_info.user_id') ? true : false;
        }else{
            $info = $this->where('contract_id=%d',$post['contract_id'])->order('check_id desc')->find();
            return $info['to_user_id']==session('user_info.user_id') ? true : false;
        }
    }

    /**
     * 根据种类查询待审批列表
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param int $user_id  用户id
     * @param array $get 查询数据
     * @param int $type 查询的种类 1我待审核的  2我已经审核的  3我已经结案申请的
     * @return mixed
     */
    public function myCheck($user_id = 0,$get = array(),$perpage=10){
        $p = $get['p'] ? $get['p'] : 1;
        $start_per=($p-1)*$perpage;
        $check_process = C('CHECK_PROCESS');

        //找出下一个审核人是自己,并且审核记录有效的信息,统计出合同id
        $check_where['is_valid']=array('eq',1);
        $check_where['to_user_id']=array('eq',$user_id);
        $count = D('Check')->where($check_where)->count();
        $Page = new \Think\Page($count,$perpage);
        //统计所有的审核,关联出业绩信息
        $checkInfos=$this->query("SELECT ch.check_id,ch.contract_id,ch.achievement_id,ch.check_user_id,ch.to_user_id,ch.check_status,ch.create_time,
ch.check_process,ch.next_process,ch.is_valid,ac.user_id,ac.status,ac.agreement_id,ac.contract_info,ac.agreement_info
FROM `".C('DB_PREFIX')."check` ch LEFT JOIN ".C('DB_PREFIX')."achievement ac
ON ch.achievement_id=ac.achievement_id
WHERE ch.is_valid=1 AND ch.to_user_id=$user_id AND ac.user_id<>$user_id order BY ch.create_time DESC LIMIT $start_per,$perpage ");

        //找出序列化居间协议数组
        $agreementInfos=array_column($checkInfos,'agreement_info');
        //将整个居间协议数组反序列化
        $agreementInfos_arr=array();
        foreach($agreementInfos as $key=>$value){$agreementInfos_arr[]=unserialize($value);}
        //通过居间协议数组查找客户信息
        $agreementKeyToCustomer=(new AgreementModel())->getCustomerInfoByAgreements($agreementInfos_arr);

        foreach($checkInfos as $k=>$v){
            $contractInfo=unserialize($v['contract_info']);
            $checkInfos[$k]['customer_name']=$agreementKeyToCustomer[$v['agreement_id']]['customer_name'];
            $checkInfos[$k]['contract_type']=$contractInfo['contract_type'];
            $checkInfos[$k]['check_user_name']=D('User')->getUserInfoByID($v['check_user_id'])['name'];
            $checkInfos[$k]['to_user_name']=D('User')->getUserInfoByID($v['to_user_id'])['name'];
            $checkInfos[$k]['check_name'] = $check_process[$v['check_process']];
            $checkInfos[$k]['next_check_name'] = $check_process[$v['next_process']];
            $checkInfos[$k]['create_user_name']=D('User')->getUserInfoByID($v['user_id'])['name'];
        }
        $return[0] = $checkInfos;
        $return[1] = $Page->show();
        return $return;
    }


    /**
     * 根据种类查询待审批列表
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param int $user_id  用户id
     * @param array $get 查询数据
     * @param int $type 查询的种类 1我待审核的  2我已经审核的  3我已经结案申请的
     * @return mixed
     */
    public function myAlreadyCheck($user_id = 0,$get = array(),$perpage=10){
        $p = $get['p'] ? $get['p'] : 1;
        $start_per=($p-1)*$perpage;
        $check_process = C('CHECK_PROCESS');
        $status = array(-2=>'被退回',-1=>'审核中',0=>'已结束',1=>'已结束');

        $check_where['check_process']=array('neq',1);
        $check_where['check_user_id']=array('eq',$user_id);
        $count = D('Check')->where($check_where)->count();
        $Page = new \Think\Page($count,$perpage);
        //统计所有的审核,关联出居间协议的id
        $checkInfos=$this->query("SELECT ch.check_id,ch.contract_id,ch.achievement_id,ch.check_user_id,ch.to_user_id,ch.check_status,ch.create_time,
ch.check_process,ch.next_process,ch.is_valid,ac.user_id,ac.status,ac.agreement_id,ac.contract_info,ac.agreement_info
FROM `".C('DB_PREFIX')."check` ch LEFT JOIN ".C('DB_PREFIX')."achievement ac
ON ch.achievement_id=ac.achievement_id
WHERE ch.check_process<>1 AND ch.check_user_id=$user_id order BY ch.create_time DESC LIMIT $start_per,$perpage ");

        //找出序列化居间协议数组
        $agreementInfos=array_column($checkInfos,'agreement_info');
        //将整个居间协议数组反序列化
        $agreementInfos_arr=array();
        foreach($agreementInfos as $key=>$value){$agreementInfos_arr[]=unserialize($value);}
        //通过居间协议数组查找客户信息
        $agreementKeyToCustomer=(new AgreementModel())->getCustomerInfoByAgreements($agreementInfos_arr);

        foreach($checkInfos as $k=>$v){
            $contractInfo=unserialize($v['contract_info']);
            $checkInfos[$k]['customer_name']=$agreementKeyToCustomer[$v['agreement_id']]['customer_name'];
            $checkInfos[$k]['check_num_name']=$contractInfo['check_num']==2?'第二轮':'第一轮';
            $checkInfos[$k]['contract_type']=$contractInfo['contract_type'];
            $checkInfos[$k]['check_user_name']=D('User')->getUserInfoByID($v['check_user_id'])['name'];
            $checkInfos[$k]['to_user_name']=D('User')->getUserInfoByID($v['to_user_id'])['name'];
            $checkInfos[$k]['check_name'] = $check_process[$v['check_process']];
            $checkInfos[$k]['next_check_name'] = $check_process[$v['next_process']];
            $checkInfos[$k]['create_user_name']=D('User')->getUserInfoByID($v['user_id'])['name'];
            $checkInfos[$k]['status'] = $status[$v['status']];
        }

        $return[0] = $checkInfos;
        $return[1] = $Page->show();
        return $return;
    }


    /**
     * 我结案的
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param int $user_id  用户id
     * @param array $get 查询数据
     * @param int $type 查询的种类 1我待审核的  2我已经审核的  3我已经结案申请的
     * @return mixed
     */
    public function myAlreadyBegin($user_id = 0,$get = array(),$perpage=10){
        $p = $get['p'] ? $get['p'] : 1;
        $check_process = C('CHECK_PROCESS');
        $status = C('CONTRACT_STATUS');

        //在自己的业绩表中查找
//        $where=array('user_id'=>$user_id);
        $where['user_id']  = array('eq',$user_id);
        $where['status']  = array('in','-1,0,1');

        $count = D('Achievement')->where($where)->count();
        $Page = new \Think\Page($count,$perpage);

        $list=D('Achievement')->Page($p,$perpage)->where($where)->order('check_time desc,create_time desc')->select();


        //找出序列化居间协议数组
        $agreementInfos=array_column($list,'agreement_info');
        //将整个居间协议数组反序列化
        $agreementInfos_arr=array();
        foreach($agreementInfos as $key=>$value){$agreementInfos_arr[]=unserialize($value);}
        //通过居间协议数组查找客户信息
        $agreementKeyToCustomer=(new AgreementModel())->getCustomerInfoByAgreements($agreementInfos_arr);


        foreach($list as $key=>$value){
            $list[$key]['customer_name']=$agreementKeyToCustomer[$value['agreement_id']]['customer_name'];
            $contractInfo=unserialize($value['contract_info']);
            $list[$key]['contract_id']=$contractInfo['contract_id'];

            $list[$key]['contract_type']=$contractInfo['contract_type'];

            if($list[$key]['status']==-1){
                $list[$key]['contract_status']='进行中';
                //找最新的一条审核记录
                $check_info = $this->where(array('contract_id'=>$contractInfo['contract_id']))->order('check_id desc')->find();
                $list[$key]['next_check_name'] = $check_process[$check_info['next_process']];
            }elseif($list[$key]['status']==0||$list[$key]['status']==1){
                $list[$key]['contract_status']='已结束';
                //找审核通过,并且财务审批的最后一条审核记录
                $check_info = $this->where(array('contract_id'=>$contractInfo['contract_id'],'check_process'=>4,'check_status'=>0))->order('check_id desc')->find();
            }

            $list[$key]['check_num_name']=$contractInfo['check_num']==1?'第一轮':'第二轮';

            $list[$key]['check_user_name']=D('User')->getUserInfoByID($check_info['check_user_id'])['name'];
            $list[$key]['check_name'] = $check_process[$check_info['check_process']];

            $list[$key]['check_status'] = $check_info['check_status'];
            $list[$key]['check_create_time'] = $check_info['create_time'];
        }
        $return[0] = $list;
        $return[1] = $Page->show();
        return $return;
    }

    /**
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * 提交申请和审核生成审核记录
     * @param $post
     * @param $achievement_id
     * @return bool
     */
    public function addCheck($post,$achievementInfo){
        //找出上一条审核记录,添加记录成功后,将上一条记录标记为失效
        $beforeCheckInfo=$this->where(array('contract_id'=>I('post.contract_id')))->order('check_id desc')->find();
        if(!$post){return false;}
        if(!$achievementInfo){return false;}
        $this->create($post);
        $this->create_time=time();
        $this->achievement_id=$achievementInfo['achievement_id'];
        $this->check_process=$post['check_process']+1;                 //当前审核的状态
        $this->check_user_id=session('user_info.user_id');
        if($post['next_process'] == 0 || $post['next_process'] == -4){       //回到原始状态
            $this->next_process = 1;
        }
        $this->startTrans();
        if($this->add()){
            //找出上一条审核记录,添加记录成功后,将上一条记录标记为失效
            if($beforeCheckInfo){
                $this->where(array('check_id'=>$beforeCheckInfo['check_id']))->save(array('is_valid'=>0));
            }
            //审核记录添加修改成功了,就要进行合同审核逻辑处理
            if((new ContractModel())->contractCheck($post)){
                $this->commit();           // 事务提交
                return true;
            }else{
                $this->rollback();         // 事务
                return false;
            }
        }else{
            $this->rollback();              // 事务
            return false;
        }
    }

}