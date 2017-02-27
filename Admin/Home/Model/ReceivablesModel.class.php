<?php
/**
 * Created by PhpStorm.
 * User: dingwenzi
 * Date: 2016/7/12
 * Time: 14:04
 */

namespace Home\Model;
use Think\Model;
class ReceivablesModel extends Model
{
    /**
     * @var array   定义写入规则
     */
    protected $_validate = array(
        array('commission','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','服务费必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('rate','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','利息必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('principal','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','本金必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('return_fee','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','返费必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('deposit','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','定金必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('other_fee','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','其他金额必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('money','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','支付金额必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('packing','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','材料费必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('channel_money','/^(-)?[0-9]{1,8}(.[0-9]{1,2})?$/','渠道费用必须是有效的值,可以精确到小数点两位,整数位不能大于10位'),
        array('time','/[0-9]{4,}/','请正确选择支付时间'),
    );

    /**
     * 合同回款列表
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param   int    $contract_id   合同id
     * @return bool|mixed
     * 合同界面的回款列表,是所有有用的回款 ,思路
     * 取出这个合同所有的回款  array1
     * 外加一条居间协议的预收款 array2  合并array1和array2为array1
     * 查看这个合同所有的业绩中的回款 array3
     * 提出array3 中的所有有主键 ,再把这些数据从array1中剔除得到array4
     * 将array4和array3合并输出
     *
     */
    public function tableList($contract_id = 0){
        if(!$contract_id){ return false; }
        //读出这个合同所有的回款,用回款id作为主键
        $where['contract_id']=array('eq',$contract_id);
        $where['achievement_id']=array('neq',-2);
        $searchArray1 = $this->where($where)->select();
        $array1=array();
        foreach($searchArray1 as $k=>$v){
            $v['searchFrom']='receivables';
            $array1[$v['receivables_id']]=$v;
        }
        //找到它签居间协议时写进去的预收款
        $contract_info = M('contract')->where('contract_id = %d',$contract_id)->find();
        $agreementOneReceivables=M('receivables')->where(array('agreement_id'=>$contract_info['agreement_id'],'contract_id'=>0,'achievement_id'=>-1))->find();
        //不是空的,合并array1和array2为array1
        if($agreementOneReceivables){
            $array2=array();
            $array2[$agreementOneReceivables['receivables_id']]=$agreementOneReceivables;
            foreach( $array2 as $key => $value ) {
                $value['searchFrom']='receivables';
                $array1[$key] = $value;
            }
        }

        //找出业绩中的所有回款,并且以回款id为主键
        $where['contract_id']=array('eq',$contract_id);
        $where['status']=array('neq',-2);
        $achievementInfo=M('Achievement')->where($where)->order('create_time desc')->select();
        $searchArray3=array();
        foreach($achievementInfo as $k=>$value){
            $searchArray3=array_merge($searchArray3,unserialize($value['receivables_infos']));
        }
        $array3=array();
        foreach($searchArray3 as $k=>$v){
            $v['searchFrom']='achievement';
            $array3[$v['receivables_id']]=$v;
        }
        //下面开始合并数组array1和array3,array3的数据不能更改
        $array4=$array3;
        foreach($array1 as $k=>$value){
            //如果不在总的数组中,添加
            if(!array_key_exists($k,$array4)){
                $array4[$k]=$value;
            }
        }

        //下面找出相关的居间协议号码
        $agreementIds=array_column($array4,'agreement_id');
        $agreementInfo=array();
        if($agreementIds){
            $agreementId=$agreementIds[0];
            $agreementInfo=(new AgreementModel())->findByWhere(array('agreement_id'=>$agreementId));
        }
        foreach($array4 as $key=>$value){
            $array4[$key]['agreementNum']=$agreementInfo['number'];
        }
        return $array4;
    }

    /**
     * 回款列表
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param   int    $contract_id   合同id
     * @return bool|mixed
     */
    public function receivablesList($contract_id = 0){
        $contract_id = intval($contract_id);
        if(!$contract_id){ return false; }
        $contract_info = M('contract')->where('contract_id = %d',$contract_id)->find();
        if($contract_info['agreement_id'] != false){
            if($contract_info['check_process'] == 0){
                $where['agreement_id']=array('eq',$contract_info['agreement_id']);
                $where['achievement_id']=array('neq',-2);
                $list = $this->where($where)->select();
                foreach($list as $k=>$v){$list[$k]['searchFrom']='receivables';}
            }else{
                $where['agreement_id']=array('eq',$contract_info['agreement_id']);
                $where['status']=array('neq',-2);
                $achievementInfo=M('Achievement')->where($where)->order('create_time desc')->select();
                $list=array();
                foreach($achievementInfo as $k=>$value){
                    $list=array_merge($list,unserialize($value['receivables_infos']));
                }
                foreach($list as $k=>$v){$list[$k]['searchFrom']='achievement';}
            }

            $agreementIds=array_column($list,'agreement_id');
            $agreementInfo=array();
            if($agreementIds){
                $agreementId=$agreementIds[0];
                $agreementInfo=(new AgreementModel())->findByWhere(array('agreement_id'=>$agreementId));
            }
            foreach($list as $key=>$value){
                $list[$key]['agreementNum']=$agreementInfo['number'];
            }
            if($list){
                return $list;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 添加合同
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param   array  $post   添加页面post过来的数据
     * @return  bool
     */
    public function receivablesAdd($post = array()){
        if(!$post){ return false; }
        //计算总支付金额,之前都是前台计算
        $post['pay_money']=$post['commission']+$post['deposit']-$post['packing']-$post['channel_money']+$post['return_fee']+$post['other_fee'];
        $post['pay_time']=strtotime($post['pay_time']);
        $post['create_user_id']=$_SESSION['user_info']['user_id'];
        $post['create_time']=time();
        if($this->create($post)&&$this->addOrSave($post)){
            return true;
        }else{
            return $this->getError();
        }
    }

    /**
     * 添加或者修改数据
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $post
     * @return bool
     */
    function addOrSave($post=array()){
        if($post['receivables_id']){
            return $this->save($post)?true:false;
        }else{
            return $this->add($post)?true:false;
        }
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
            return $result;
        }
        return false;
    }

    /**
     * 通过条件查找
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param array $where
     * @return bool|mixed
     */
    public function selectByWhere($where = array())
    {
        $result = $this->where($where)->select();
        if (!empty($result)) {
            return $result;
        }
        return false;
    }
}