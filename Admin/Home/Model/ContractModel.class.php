<?php
/**
 * Created by PhpStorm.
 * User: ZengZhiQiang
 * Date: 2016/08/08
 * Time: 14:04
 */

namespace Home\Model;
use Home\Logic\CheckLogic;
use Think\Model;
class ContractModel extends Model
{

    /**
     * 根据条件查找
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $where
     * @return bool|mixed
     */
    public function findByWhere($where = array())
    {
        $result = $this->where($where)->find();
        if (!empty($result)) {
            return $result;
        }
        return false;
    }
    /**
     * 合同列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param   array    $get    查询get数据  $get['condition']、$get[$get['condition']] 、$get['p']
     * @users  要查询的员工,用,分割
     * @return  $return[0] = $list;             员工列表
     * @return  $return[1] = $Page->show();     分页信息
     */
    public function contractList($get = array(),$users){
        $status = C('CONTRACT_STATUS');
        $check_process = C('CHECK_PROCESS');
        $check_process_status = C('CHECK_PROCESS_STATUS');
        //构建查询条件
        $user_arr= explode(',',$users);
        $where["create_user_id"] = array('in',$user_arr);
        $where["borrower"] = array('like','%'.$get["borrower"].'%');
        if($get['phone']){$where["phone"]  = array('eq',$get['phone']);}
        if($get['status']!=''){$where["status"]  = array('eq',$get['status']);}
        if($get['create_time']){
            $where["create_time"]  = array('between',array(splitDate($get['create_time'])[0],splitDate($get['create_time'])[1]));
        }
        if($get['contract_type']){$where["contract_type"]  = array('eq',$get['contract_type']);}

        $list = $this->where($where)->select();
        $count = count($list);
        $Page = new \Think\Page($count,15);
        $p = $get['p'] ? $get['p'] : 1 ;  //获得页码
        $list = $this->Page($p,15)->where($where)->order('create_time desc')->select();
        if($list){
            $userModel=new UserModel();
            foreach ($list as $k=>$v){
                $list[$k]['create_date'] = $v['create_time'] ? date('Y-m-d',$v['create_time']) : '';
                $list[$k]['status_name'] = $status[$v['status']];
                $list[$k]['process_status'] = $check_process_status[$v['check_process']];
                $list[$k]['check_process_name'] = $check_process[$v['check_process']+1];
                $list[$k]['check_name'] = $v['check_status'] ? "驳回" : "申请" ;

                $userinfo=$userModel->getUserInfoByID($v['create_user_id']);
                $list[$k]['user_name']=$userinfo['name'];
            }
        }
        $return[0] = $list;
        $return[1] = $Page->show();
        return $return;
    }

    /**
     * 查询合同详细信息
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param int $contract_id
     * @return bool|mixed
     * @return array $contract_info   合同信息
     */
    public function contractInfo($contract_id = 0){
        if(!$contract_id){ return false; }
        $status = C('CONTRACT_STATUS');
        $check_process = C('CHECK_PROCESS');
        if($contract_info = $this->find($contract_id)){
            $contract_info['status_name'] = $status[$contract_info['status']];
            $contract_info['give_date'] = $contract_info['give_time']?date('Y-m-d',$contract_info['give_time']):"";
            $contract_info['give_time_two'] = $contract_info['give_time_two']?date('Y-m-d',$contract_info['give_time_two']):"";
            $contract_info['expire_time'] = $contract_info['give_time']?date('Y-m-d',$contract_info['expire_time']):"";
            $contract_info['check_name'] = $contract_info['check_status'] ? "驳回" : "申请" ;
            $contract_info['contracttype'] = $contract_info['contract_type']==1 ? '渠道' : '自营';

            $contract_info['product_type_name'] = [];
            $product_type_arr = array_filter(explode(",",$contract_info['product_name']));
            if($product_type_arr) {
                $map = [];
                $map['product_id'] = array('in', $product_type_arr);
                $product_type_name = M('Product')->field('product_id,product_name')->where($map)->select();
            }
            foreach($product_type_name as $val){
                $contract_info['product_type_name'][$val['product_id']] =  $val['product_name'];
            }
            if($contract_info['first_process'] == $contract_info['check_process']){
            }
            $contract_info['check_process_name'] = $check_process[$contract_info['check_process']+2];
        }
        return $contract_info;
    }
    /**
     * 根据条件通过接口获取用户信息
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $where
     * @param $type
     * @return mixed
     */
    public function getUserByWhere($where,$type){
        $where=serialize($where);
        $arr=array('where'=>$where,'type'=>$type);
        $return=json_decode(post_url(C('BASE_getUserInfo'),$arr),true);
        return $return['retData'];
    }
    /**
     * 根据用户id通过接口获取包括自己的所有下属
     * @author <dingwenzai@fangjinsuo.com>
     * @param $userid
     * @return mixed
     */
    public function getSubUserByUserId($userid,$type='sale'){
        if(session("user_info.data_type") == 1){      //权限  仅查看自己的数据
            $return['retData'] = array(0=>session("user_info.user_id"));
        }else{
            $arr=array('user_id'=>$userid,'data_type'=>session("user_info.data_type"));
            $return=json_decode(post_url(C('BASE_getSubUserByUserId'),$arr),true);
        }
        return $return['retData'];
    }
    /**
     * 根据用户id通过接口获取所在城市
     * @author <dingwenzai@fangjinsuo.com>
     * @param $userid
     * @return mixed
     */
    public function getCityByUserid($userid){
        $arr=array('user_id'=>$userid);
        $return=json_decode(post_url(C('BASE_getCityByUserid'),$arr),true);
        return $return['retData'];
    }
    /**
     * 根据关键词通过接口查找值
     * @author <dingwenzai@fangjinsuo.com>
     * @param $name
     * @param $value
     * @return mixed
     */
    public function getRoleInfoBy($name,$value){
        $arr=array('name'=>$name,'value'=>$value);
        $return=json_decode(post_url(C('BASE_getRoleInfo'),$arr),true);
        return $return['retData'];
    }
    /**
     * 根据关键词通过接口查询配置值
     * @author <dingwenzai@fangjinsuo.com>
     * @param $names
     * @return mixed
     */
    static public function getConfig($names){
        $arr=array('names'=>$names);
        $return=json_decode(post_url(C('BASE_getConfig'),$arr),true);
        return $return['retData'];
    }
    /**
     * 添加合同
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param   array  $post   添加页面post过来的数据
     * @return  bool
     * 创建合同的时候,将客户的状态改为已经签合同
     */
    public function contractAdd($post = array()){
        if(!$post){ return false; }
        $post['give_time']=strtotime($post['give_time']);
        if($post['give_time_two']){$post['give_time_two']=strtotime($post['give_time_two']);}
        $post['expire_time']=strtotime($post['expire_time']);
        $agreementInfo=M('Agreement')->where(array('agreement_id'=>$post['agreement_id']))->find();
        if(!$agreementInfo){return false;}

        D('Agreement')->startTrans();
        //将居间协议更新为已签协议状态-1
        if(D('Agreement')->updataAgreementStatus($agreementInfo,1)){
            //添加合同信息并且更新客户状态
            if($this->contractAddAndUpdataCustomer($post,$agreementInfo['customer_id'])){
                D('Agreement')->commit();
                return true;
            }else{
                D('Agreement')->rollback();
                return false;
            }
        }else{
            D('Agreement')->rollback();
            return false;
        }
    }

    /**
     * 添加合同并且更新客户状态
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $post
     * @param $customer_id
     * @return bool
     */
    public function contractAddAndUpdataCustomer($post,$customer_id){
        $this->create($post);
        $this->create_user_id = $_SESSION['user_info']['user_id'];
        $this->create_time = time();
        $this->updata_time = time();
        $this->check_process = 0;
        $this->status = 0;
        $this->is_delete = 0;
        $this->startTrans();
        if($this->add()){
            $where['customer_type'] = array("neq","成交客户");
            $where['customer_id'] = array("eq",$customer_id);
            $return=M('CustomerSale')->where($where)->save(array('customer_type'=>'成交客户'));
            if($return !== false){
                $this->commit();
                return true;
            }else{
                $this->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /**
    * 编辑员工信息
    * @author <zengzhiqiang@fangjinsuo.com>
    * @createdate: 2016-07-14
    * @param   array  $post   编辑页面post过来的数据
    * @return  bool
    */
    public function contractEdit($post = array()){
        if(!$post){ return false; }
        $post['give_time']=strtotime($post['give_time']);
        if($post['give_time_two']){$post['give_time_two']=strtotime($post['give_time_two']);}
        $post['expire_time']=strtotime($post['expire_time']);
        $post['updata_time']=time();
        $this->create($post);
        if(FALSE !== $this->save()){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 判断有没有权利处理
     * @param $contract_id
     * @return bool
     * 合同在审核中和审核结束的合同不能处理
     */
    public function HasPowerToDo($contract_id){
        $contractInfo=$this->where(array('contract_id'=>$contract_id))->find();
        if($contractInfo['create_user_id']!=session('user_info.user_id')){return '您没有权限';}
        if(($contractInfo['status']==1&&$contractInfo['contract_type']==1)||$contractInfo['status']==2){
            return '此合同状态不允许操作';
        }
        return true;
    }
    /**
    * 删除合同
    * @author <zengzhiqiang@fangjinsuo.com>
    * @createdate: 2016-07-14
    * @param   int  $user_id   员工id
    * @return  bool
    */
    public function contractDelete($contract_id = 0){
        if(!$contract_id){ return false; }
        if($this->where('contract_id = %d',$contract_id)->setField('is_delete',1)){
            return true;
        }else{
            return false;
        }
    }
    /**
    * 删除合同
    * @author <zengzhiqiang@fangjinsuo.com>
    * @createdate: 2016-07-14
    * @param   int  $user_id   员工id
    * @return  bool
    */
    public function getAgreementLock($agreement_id = 0){
        if(!$agreement_id){ return false; }
        if($this->where('agreement_id = %d',$agreement_id)->find()){
            return true;
        }else{
            return false;
        }
    }



    /**
     * 合同审核模型
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $post
     * @return bool
     */
    public function contractCheck($post){
        if(!$post['contract_id']){ return false; }
        $contract_id = $post['contract_id'];
        $contract_info = $this->where('contract_id = %d',$contract_id)->find();

//        //如果是申请结案,提前生成业绩
//        if($contract_info['check_process']==0){
//            if(!D('Achievement')->achievementAdd($contract_info)){return false;}
//        }

        //合同已结束
        if(!$contract_info || $contract_info['status'] == 2){return false; }
        //下面是能审核的合同
        if(($contract_info['contract_type']==1 && $contract_info['check_num']==1)||($contract_info['contract_type']==2 && $contract_info['check_num']<=C('SELF_RECEIVABLES_NUM'))){

            switch($post['next_process']){
                case -4://进去下一轮审核  生效业绩,修改合同状态
                    $info=array('check_process'=>0,'status'=>0,'updata_time'=>time(),'check_num'=>$contract_info['check_num']+1,'check_status'=>$post['check_status']);
                    return $this->checkAdd($contract_id,$info,$contract_info)?true:false;
                    break;
                case 0://被驳回到原始状态  不结算业绩
                    $info=array('check_process'=>0,'status'=>0,'updata_time'=>time(),'check_status'=>$post['check_status']);
//                    $this->startTrans();        //事务开始
                    if($this->where('contract_id = %d',$contract_id)->save($info)){
                        //同时将之前生成的未生效的业绩标记为废弃状态
                        $updataAchievement=M('Achievement')->where(array('agreement_id'=>$contract_info['agreement_id'],'status'=>-1))->save(array('status'=>-2));
                        if($updataAchievement){
//                            $this->commit();     // 事务提交
                            return true;
                        }else{
//                            $this->rollback();     // 事务回滚
                            return false;
                        }
                    }else{
                        return false;
                    }
                    break;
                case -1://审核结束   生效业绩
                    $info=array('check_process'=>count(C('ALLUSER_ROLE')),'status'=>2,'updata_time'=>time(),'check_status'=>$post['check_status'],'check_num'=>$contract_info['check_num']+1);
                    return $this->checkAdd($contract_id,$info,$contract_info)?true:false;
                    break;
                default://默认的审核通过 和 驳回
                    $roles=(new UserModel())->getUserRoleNames($contract_info['create_user_id']);
                    $myBestRoleKey=(new CheckLogic())->myBestRoleKey($roles);

                    if($post['check_status']){          //驳回
                        $info['check_process'] = $post['check_process']-1;
                        if($info['check_process']<$myBestRoleKey){$info['check_process']=0;}
                    }else{                              //同意
                        $info['check_process'] =$post['check_process']==0?$myBestRoleKey:$post['check_process']+1;
                    }
                    $info['check_status'] = $post['check_status'];
                    $info['updata_time'] = time();
                    if($info['check_process'] == 4){    //下一轮审核   生效业绩,修改合同状态
                        $info['check_process'] = 0;
                        $info['status'] = 0;
                        $info['check_num'] = $contract_info['check_num']+1;
                        return $this->checkAdd($contract_id,$info,$contract_info)?true:false;
                    }else{
                        $info['status'] = 1;
                        return $this->where('contract_id = %d',$contract_id)->save($info)?true:false;
                    }
            }
        }else{
            return false;
        }
    }

    /**
     * 生效业绩前,更改合同的状态
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $contract_id
     * @param $info
     * @param $contract_info
     * @return bool
     */
    public function checkAdd($contract_id,$info,$contract_info){
//        $this->startTrans();        //事务开始
        if($this->where('contract_id = %d',$contract_id)->save($info)){
            //更改业绩状态
            if($this->updataAchievementToUse($contract_info)){
//                $this->commit();     // 事务提交
                return true;
            }else{
//                $this->rollback();     // 事务回滚
                return false;
            }
        }else{
//            $this->rollback();     // 事务回滚
            return false;
        }
    }


    /**
     * 更改业绩状态为启用
     * 更改成功后,将回款全部标记为0-财务已经审核
     * @param $contract_info 合同信息
     * @return bool
     */
    public function updataAchievementToUse($contract_info){
        $achievementInfo=D('Achievement')->where(array('agreement_id'=>$contract_info['agreement_id']))->order('create_time desc')->find();
        $achievementInfo['status']=0;
        $achievementInfo['check_time']=time();

        $receivables_list=unserialize($achievementInfo['receivables_infos']);
        $receivables_ids=array();

        //更改业绩状态为启用
//        M('Achievement')->startTrans();        //事务开始
        if(D('Achievement')->save($achievementInfo)){
            //如果存在回款记录,更改这条业绩的所有回款记录的achievement_id 为0
            if($receivables_ids){
                foreach($receivables_list as $value){
                    $receivables_ids[]=$value['receivables_id'];
                }
                $where['receivables_id']=array('in',$receivables_ids);
                if(M('Receivables')->where($where)->save(array('achievement_id'=>0))){
//                    M('Achievement')->commit();     // 事务提交
                    return true;
                }else{
//                    M('Achievement')->rollback();     // 事务回滚
                    return false;
                }
            }
//            M('Achievement')->commit();     // 事务提交
            return true;
        }else{
//            M('Achievement')->rollback();     // 事务回滚
            return false;
        }
    }
}