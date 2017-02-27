<?php
/**
 * Created by PhpStorm.
 * User: ZengZhiQiang
 * Date: 2016/08/08
 * Time: 14:04
 */

namespace Home\Model;
use Think\Model;
class AchievementModel extends Model
{
    /**
     * 员工列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @users: 用户id组
     * @createdate: 2016-07-14
     * @param   array    $get    查询get数据  $get['condition']、$get[$get['condition']] 、$get['p']
     * @return  $return[0] = $list;             员工列表
     * @return  $return[1] = $Page->show();     分页信息
     *
     */
    public function achievementList($get = array(),$users=''){
        $user_arr= explode(',',$users);
        //构建条件
        $where["user_id"] = array('in',$user_arr);
        if ($get['agreement_number']) {       //有没有查询居间协议查询
            $agreementInfo=D('Agreement')->where(array('number'=>$get['agreement_number']))->find();
            $where['agreement_id'] = array("eq", $agreementInfo['agreement_id']);
        }
        if($get['achievement_date']){          //查询时间段
            $where["create_time"]  = array('between',array(splitDate($get['achievement_date'])[0],splitDate($get['achievement_date'])[1]));
        }
        $where['status'] = array("in", array(0,1));   //生效的业绩

        $list = $this->where($where)->select();
        $count = count($list);
        $Page = new \Think\Page($count,10);
        $p = $get['p'] ? $get['p'] : 1 ;        //获得页码
        $list = $this->page($p)->where($where)->order('check_time desc')->select();
        $userModel=new UserModel();
        foreach ($list as $k=>$v){
            $userinfo=$userModel->getUserInfoByID($v['user_id']);
            $list[$k]['user_name']=$userinfo['name'];
            $list[$k]['agreement'] = unserialize($v['agreement_info']);
            $list[$k]['contract'] = unserialize($v['contract_info']);
        }
        $return[0] = $list;                     //员工列表
        $return[1] = $Page->show();             //分页信息
        return $return;
    }
    /**
     * 生成未生效的业绩记录
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-16
     * @param array contract_info 合同的信息
     * @return bool
     */
    public function achievementAdd($contract_info=array()) {
        if(!$contract_info){ return false; }
        $data['agreement_id'] = $contract_info['agreement_id'];
        $data['user_id'] = $contract_info['create_user_id'];
        $where_r['agreement_id'] = $contract_info['agreement_id'];
        $where_r['check_num'] = $contract_info['check_num'];
        $where_r['achievement_id'] = -1;
        $receivables_list = M('receivables')->where($where_r)->select();
        //如果回款是空的就不能提交申请
        if(empty($receivables_list)){return false;}
        $arr = array('commission'=>0,'packing'=>0,'channel_money'=>0,'return_fee'=>0,'principal'=>0,'rate'=>0,'deposit'=>0);
        foreach ($receivables_list as $k=>$v){
            $arr['commission'] += $v['commission'];         //佣金(服务费)
            $arr['deposit'] += $v['deposit'];               //预付款
            $arr['packing'] += $v['packing'];               //材料费
            $arr['channel_money'] += $v['channel_money'];   //返费支出
            $arr['return_fee'] += $v['return_fee'];         //返费收入

            $arr['principal'] += $v['principal'];           //本金
            $arr['rate'] += $v['rate'];                     //利息

        }
        $data['commission_total'] = $arr['commission'];     //总佣金
        $data['commission_net'] = $arr['commission'] - $arr['packing'] - $arr['channel_money'] + $arr['return_fee'];    //净佣金
        $data['deposit'] = $arr['deposit'];                 //预付款
        $data['packing'] = $arr['packing'];                 //材料费
        $data['channel_money'] = $arr['channel_money'];     //返费支出
        $data['return_fee'] = $arr['return_fee'];           //返费收入
        $data['principal'] = $arr['principal'];             //本金
        $data['rate'] = $arr['rate'];                       //利息

        $agreement_info = M('agreement')->where('agreement_id = %d',$contract_info['agreement_id'])->find();
        $data['agreement_info'] = serialize($agreement_info);
        $data['contract_info'] = serialize($contract_info);
        $data['receivables_infos'] = serialize($receivables_list);
        $data['status'] = -1;
        $data['create_time'] = time();
        $data['check_time'] = time();
        $data['contract_id'] = $contract_info['contract_id'];

        $return=$this->add($data);
        if($return){
            $data['achievement_id']=$return;
            return $data;
        }else{
            return false;
        }
    }
    /**
     * 确认业绩
     * 确认后,修改和业绩相关的回款记录为这业绩的id
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-15
     * @param int $achievement_id
     * @return bool
     */
    public function achievementCheck($achievement_id = 0) {
        if (!$achievement_id) { return false; }
        $achievement_info = $this->where('achievement_id = %d', $achievement_id)->find();
        if($achievement_info['user_id'] != $_SESSION['user_info']['user_id']){return '您没有权限';}

        $this->startTrans();        //事务开始
        $receivables_list=unserialize($achievement_info['receivables_infos']);
        $receivables_ids=array();
        foreach($receivables_list as $value){
            $receivables_ids[]=$value['receivables_id'];
        }
        $where['receivables_id']=array('in',$receivables_ids);
        $data['status'] = 1;
        $data['confirm_time'] = time();
        if ($this->where('achievement_id = %d', $achievement_id)->save($data)) {
            //更改这条业绩的所有回款记录的achievement_id 为业绩的id
            if(M('Receivables')->where($where)->save(array('achievement_id'=>$achievement_id))){
                $this->commit();     // 事务提交
                return true;
            }else{
                $this->rollback();     // 事务回滚
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 按条件单个查找
     * @param $where
     * @return mixed
     */
    public function findByWhere($where){
        return $this->where($where)->find();
    }
}