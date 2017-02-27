<?php
namespace  Home\Controller;
use Home\Controller\BaseController;
use Home\Model\ReceivablesModel;

/**
 * 回款记录 模块
 * @author <zengzhiqiang@fangjinsuo.com>
 * @createdate: 2016-08-09
 */
class ReceivablesController extends BaseController {


    /**
     * 回款记录列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-09
     */
    public function index(){
        $this->display();
    }


    /**
     * 添加回款记录
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-10
     * @param contract_id合同的id
     * @return json数据
     * 渠道类型的合同在审核中不能添加回款
     * 审核结束的合同也不能回款
     */
    public function add(){
        $contract_id=I('post.contract_id')?I('post.contract_id'):I('get.contract_id');

        $HasPowerToDo=D('Contract')->HasPowerToDo($contract_id);
        if($HasPowerToDo!==true){I('post.contract_id')?$this->errorjsonReturn($HasPowerToDo):exit($HasPowerToDo);}

        if(IS_POST){
            $return = D('Receivables')->receivablesAdd(I('post.'));
            $return===true?$this->setjsonReturn('添加成功'):$this->errorjsonReturn($return);
        }else{
            $contract_info = D('Contract')->contractInfo(I('get.contract_id'));
            $this->assign('contract_info' , $contract_info);
            $this->display();
        }
    }

    /**
     * 查询回款记录列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-09
     */
    public function table(){
        $result = (new ReceivablesModel())->tableList(I('get.contract_id'));
        $this->assign('receivables_list' , $result);
        $this->display();
    }


    /**
     * 回款记录的状态变更
     * @param $id
     * @param $toStatus
     */
    public function updataStatus($id,$toStatus){
        $ReceivablesInfo=(new ReceivablesModel())->findByWhere(array('receivables_id'=>$id));
        if($ReceivablesInfo['create_user_id']!=$this->user_id){$this->errorjsonReturn('你没有权限');}
        if(!in_array($ReceivablesInfo['achievement_id'],array(-2,-1))){$this->errorjsonReturn('状态不允许更改');}
        if(!in_array($toStatus,array(-2,-1))){$this->errorjsonReturn('您的操作有误');}
        if((new ReceivablesModel())->addOrSave(array('receivables_id'=>$id,'achievement_id'=>$toStatus))){
            $this->setjsonReturn('操作成功');
        }else{
            $this->errorjsonReturn('操作失败');
        }
    }

}