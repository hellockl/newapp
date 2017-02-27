<?php
namespace  Home\Controller;
use Home\Controller\BaseController;
use Home\Logic\CheckLogic;
use Home\Model\AchievementModel;
use Home\Model\AgreementModel;
use Home\Model\CheckModel;
use Home\Model\ContractModel;
use Home\Model\ProductModel;
use Home\Model\ReceivablesModel;
use Home\Model\UserModel;

/**
* 审核管理 模块
* @author <zengzhiqiang@fangjinsuo.com>
* @createdate: 2016-08-08
*/
class CheckController extends BaseController {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 提交结案申请界面
     * get方式
     * @author <dingwenzai@fangjinsuo.com
     * @createdate: 2016-08-08
     * @id 合同的id号
     */
    public function refer(){
        $contractInfo=D('Contract')->contractInfo(I('get.id'));
        //获取合同产品的信息
        $contractInfo['product_names']=(new ProductModel())->getProductByContract($contractInfo);
        //下面判断是不是能提交结案的状态
        if($contractInfo['check_process']!=0){exit('状态不允许');}

        //下面找出居间协议的信息
        $agreementInfo=(new AgreementModel())->findByWhere(array('agreement_id'=>$contractInfo['agreement_id']));
        //读取的回款记录是从回款记录表里面读,找出不是无效的的记录
        $receivables_where['agreement_id']=array('eq',$contractInfo['agreement_id']);
        $receivables_where['achievement_id']=array('neq',-2);
        $receivablesInfos=(new ReceivablesModel())->selectByWhere($receivables_where);
        //下面计算总的佣金
        $commission_total=0;
        foreach($receivablesInfos as $k=>$v){
            $receivablesInfos[$k]['agreementNum']=$agreementInfo['number'];
            $commission_total=$commission_total+$v['commission']+$v['return_fee']+$v['deposit'];
        }
        $this->assign('contract_info', $contractInfo);
        $this->assign('check_list', D('Check')->checkList(I('get.id')));
        $this->assign('receivables_list', $receivablesInfos);
        $this->assign('commission_total',$commission_total);
        $this->display();
    }


    /**
     * 提交结案申请动作
     * post方式
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return json数据
     * post array(7) contract_id,check_process,check_status,suggestion,next_process,to_user_id,submit
     */
    public function referAction(){
        $post=I('post.');
        $contractInfo=(new ContractModel())->findByWhere(array('contract_id'=>$post['contract_id']));
        //先判断提交权限
        if($contractInfo['check_process']!=0){$this->errorjsonReturn('状态错误');}
        if($contractInfo['create_user_id']!=$this->user_id){$this->errorjsonReturn('您没有权限');}
        //生成未生效的业绩
        $achievementInfo=(new AchievementModel())->achievementAdd($contractInfo);
        if(!$achievementInfo){$this->errorjsonReturn('请认真填写您的回款记录');}
        //生成审核记录
        if((new CheckModel())->addCheck(I('post.'),$achievementInfo)){
            $this->setjsonReturn('提交成功');
        }else{
            $this->errorjsonReturn('提交失败');
        }
    }


    /**
     * 我的审核界面
     * @author <dingwenzai@fangjinsuo.com
     * @createdate: 2016-08-08
     */
    public function myCheckOpen(){
        if(I('get.achievement_id')){$achievement_id=I('get.achievement_id');}else{exit('操作有误');}
        $achievementInfo=M('Achievement')->where(array('achievement_id'=>$achievement_id))->find();
        $old_contractInfo=unserialize($achievementInfo['contract_info']);
        //获取合同产品的信息
        $old_contractInfo['product_names']=(new ProductModel())->getProductByContract($old_contractInfo);

        //下面找出居间协议的信息
        $agreementInfo=unserialize($achievementInfo['agreement_info']);

        //查询审核流程
        $checkInfo=(new CheckModel())->selectByWhere(array('achievement_id'=>$achievement_id));
        $check_process=0;
        foreach ($checkInfo as $k=>$v){
            $check_list[$k]['check_process'] = $check_process[$v['check_process']];
            $userinfo=(new UserModel())->getUserInfoByID($v['check_user_id']);
            $check_list[$k]['check_user_name']=$userinfo['name'];
            $check_process=$v['next_process']-1;
        }

        $receivables_list=unserialize($achievementInfo['receivables_infos']);
        foreach($receivables_list as $k=>$v){
            $receivables_list[$k]['agreementNum']=$agreementInfo['number'];
        }

        $this->assign('achievement_id', $achievement_id);
        $this->assign('check_process', $check_process);
        $this->assign('commission_total',$achievementInfo['commission_total']+$achievementInfo['return_fee']+$achievementInfo['deposit']);
        $this->assign('contract_info', $old_contractInfo);
        $this->assign('check_list',$checkInfo );
        $this->assign('receivables_list', $receivables_list);
        $this->display();
    }


    /**
     * 查看审核界面
     */
    public function viewCheckOpen(){
        if(I('get.achievement_id')){$achievement_id=I('get.achievement_id');}else{exit('操作有误');}
        $achievementInfo=M('Achievement')->where(array('achievement_id'=>$achievement_id))->find();
        $old_contractInfo=unserialize($achievementInfo['contract_info']);
        //获取合同产品的信息
        $old_contractInfo['product_names']=(new ProductModel())->getProductByContract($old_contractInfo);

        //下面找出居间协议的信息
        $agreementInfo=unserialize($achievementInfo['agreement_info']);

        //查询审核流程
        $checkInfo=(new CheckModel())->selectByWhere(array('achievement_id'=>$achievement_id));
        $check_process=0;
        foreach ($checkInfo as $k=>$v){
            $check_list[$k]['check_process'] = $check_process[$v['check_process']];
            $userinfo=(new UserModel())->getUserInfoByID($v['check_user_id']);
            $check_list[$k]['check_user_name']=$userinfo['name'];
            $check_process=$v['next_process']-1;
        }
        //判断审核是不是已经结束
        if(($achievementInfo['status']==0)||($achievementInfo['status']==1)){$check_process=5;}

        $receivables_list=unserialize($achievementInfo['receivables_infos']);
        foreach($receivables_list as $k=>$v){
            $receivables_list[$k]['agreementNum']=$agreementInfo['number'];
        }

        $this->assign('achievement_id', $achievement_id);
        $this->assign('check_process', $check_process);
        $this->assign('commission_total',$achievementInfo['commission_total']+$achievementInfo['return_fee']+$achievementInfo['deposit']);
        $this->assign('contract_info', $old_contractInfo);
        $this->assign('check_list',$checkInfo );
        $this->assign('receivables_list', $receivables_list);
        $this->display();
    }


    /**
     * 查看审核信息
     * get方式
     * @author <dingwenzai@fangjinsuo.com
     * @createdate: 2016-08-08
     * @id 合同的id号
     */
    public function view(){
        $contractInfo=D('Contract')->contractInfo(I('get.id'));
        //获取合同产品的信息
        $contractInfo['product_names']=(new ProductModel())->getProductByContract($contractInfo);
        //下面找出居间协议的信息
        $agreementInfo=(new AgreementModel())->findByWhere(array('agreement_id'=>$contractInfo['agreement_id']));

        //还未提交申请情况下,读取的回款记录是从回款记录表里面读
        if($contractInfo['check_process']==0){
            $receivablesInfos=D('Receivables')->receivablesList(I('get.id'));
            $this->assign('receivables_list', $receivablesInfos);
            //下面计算总的收入
            $commission_total=0;
            foreach($receivablesInfos as $k=>$v){
                $commission_total=$commission_total+$v['commission']+$v['deposit']+$v['return_fee'];//服务费+返费收入+预付款
            }
            $receivables_list=$receivablesInfos;
        }else{//其他状态从业绩里面读
            $searchWhere['agreement_id']=array('eq',$contractInfo['agreement_id']);
            $searchWhere['status']=array('neq',-2);
            $achievementInfo=M('Achievement')->where($searchWhere)->order('create_time desc')->select();
            //计算最后业绩的总的收入
            $lastAchievementInfo=$achievementInfo[count($achievementInfo)-1];
            //服务费+返费收入+预付款
            $commission_total=$lastAchievementInfo['commission_total']+$lastAchievementInfo['return_fee']+$lastAchievementInfo['deposit'];

            $receivables_list=array();
            foreach($achievementInfo as $k=>$value){
                $receivables_list=array_merge($receivables_list,unserialize($value['receivables_infos']));
            }
        }

        foreach($receivables_list as $k=>$v){
            $receivables_list[$k]['agreementNum']=$agreementInfo['number'];
        }
        $this->assign('contract_info', $contractInfo);
        $this->assign('check_list', D('Check')->checkList(I('get.id')));
        $this->assign('receivables_list', $receivables_list);
        $this->assign('commission_total',$commission_total);
        $this->display();
    }


    /**
     * 审核
     * post方式
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return json数据
     * post array(7) contract_id,check_process,check_status,suggestion,next_process,to_user_id,submit
     */
    public function check(){
        if(!D('Check')->check_validate(I('post.'))){
            $this->errorjsonReturn('您没有权限');
        }
        if(I('post.achievement_id')){$achievement_id=I('post.achievement_id');}else{$this->errorjsonReturn('操作错误');}
        $achievementInfo=(new AchievementModel())->findByWhere(array('achievement_id'=>$achievement_id));
        if((new CheckModel())->addCheck(I('post.'),$achievementInfo)){
            $this->setjsonReturn('提交成功');
        }else{
            $this->errorjsonReturn('提交失败');
        }
    }
    /**
     * 获取上一步和下一步
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $action   操作:驳回和同意
     * @param $contract_id   合同的id
     * @return 审核人和id  $process_id和步骤的名字
     */
    public function getProcess($contract_id,$action){
        $contractInfo=D('Contract')->findByWhere(array('contract_id'=>$contract_id));
        //审核完毕的判断
        if(!(($contractInfo['contract_type']==1 && $contractInfo['check_num']==1)||($contractInfo['contract_type']==2 && $contractInfo['check_num']<=C('SELF_RECEIVABLES_NUM')))) {
            $data=array('toUsers'=>array(array('user_id'=>-1,'user_name'=>'审核完毕')),'process_id'=>-1,'process_name'=>'');
            $this->setjsonReturn($data);
        }
        //查出上一步和下一步的代号
        if($contractInfo['check_process']==0){//如果是未提交状态，从最职位开始，-1代表空
            $roles=(new UserModel())->getUserRoleNames($contractInfo['create_user_id']);
            $myBestRoleKey=(new CheckLogic)->myBestRoleKey($roles);
            $nextCheckRole=$myBestRoleKey+1;
            $beforeCheckRole=-1;
        }elseif(($action==1)&&($contractInfo['check_process']>=count(C('ALLUSER_ROLE'))-2)){
            //自营的进入第二轮重新审核
            if(($contractInfo['contract_type']==2)&&($contractInfo['check_num']<C('SELF_RECEIVABLES_NUM'))){
                $nextCheckRole=-4;
                $beforeCheckRole=$contractInfo['check_process'];
            }else{
                $nextCheckRole=0;
                $beforeCheckRole=$contractInfo['check_process'];
            }
        }else{
            $nextCheckRole=$contractInfo['check_process']+2;
            $beforeCheckRole=$contractInfo['check_process'];
            if($action!=1){
                $roles=(new UserModel())->getUserRoleNames($contractInfo['create_user_id']);
                $myBestRoleKey=(new CheckLogic())->myBestRoleKey($roles);
                if($beforeCheckRole<=$myBestRoleKey){
                    $beforeCheckRole=-3;
                }
            }
        }
        //根据操作(上一步和下一步)判断出步骤
        $CheckRole=$action==1?$nextCheckRole:$beforeCheckRole;
        //根据要操作的步骤,判断要输给前端的数据
        switch($CheckRole){
            case -4:
                $data=array('toUsers'=>array(array('user_id'=>$contractInfo['create_user_id'],'user_name'=>'创建人')),
                    'process_id'=>-4,'process_name'=>'第二轮结案申请');break;
            case -3:
                $data=array('toUsers'=>array(array('user_id'=>$contractInfo['create_user_id'],'user_name'=>'创建人')),
                    'process_id'=>0,'process_name'=>'结案申请');break;
            case -1:
                $data=array('toUsers'=>array(array('user_id'=>-1,'user_name'=>'')),'process_id'=>-2,'process_name'=>'');break;
            case 0:
                $data=array('toUsers'=>array(array('user_id'=>-1,'user_name'=>'审核完毕')),'process_id'=>-1,'process_name'=>'审核完毕');break;
            default:
                //查找角色对应的角色id
                $result= D('Contract')->getRoleInfoBy('name',C('ALLUSER_ROLE')[$CheckRole]);
                $team_role_id = $result['role_id'];
                //找出部门id号
                $userinfo=(new UserModel())->getUserInfoByID($contractInfo['create_user_id']);
                $sub_company_id=$userinfo['sub_company_id'];
                $department_id=$userinfo['department_id'];

                $map['_string'] = '(role_ids like "%,'.$team_role_id.',%") OR ( role_ids like "%,'.$team_role_id.'") OR ( role_ids like "'.$team_role_id.',%")  OR ( role_ids  = '.$team_role_id.')';
                //如果是财务,不用查分公司,是所有公司
                if(!(($action==1)&&($contractInfo['check_process']==(count(C('ALLUSER_ROLE'))-3)))){
                    if($CheckRole==2){//要团队长审核,查询部门id
                        $map['department_id']=$department_id;
                    }else{//查询分公司id
                        $map['sub_company_id'] = $sub_company_id;
                    }
                }
                $map['status']=1;//在职
                $check_userinfo=D('Contract')->getUserByWhere($map,2);
                $toUsers=array();
                foreach($check_userinfo as $value){
                    $toUsers[]=array('user_id'=>$value['user_id'],'user_name'=>$value['name']);
                }
                $data=array('toUsers'=>$toUsers,'process_id'=>$CheckRole,'process_name'=>C('ALLUSER_ROLE')[$CheckRole]);
        }
        $this->setjsonReturn($data);
    }


    /**
     * 我的待审批列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式-查询条件
     */
    public function myCheck(){
        $return = (new CheckModel())->myCheck($this->user_id,I('get.'));
        $this->assign('check_list' , $return[0]);
        $this->assign('pageBar', $return[1]);
        $this->display();
    }

    /**
     * 我的已经审批列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式-查询条件
     */
    public function myAlreadyCheck(){
        $return = (new CheckModel())->myAlreadyCheck($this->user_id,I('get.'));
        $this->assign('check_list' , $return[0]);
        $this->assign('pageBar', $return[1]);
        $this->display();
    }


    /**
     * 我已经提交申请的列表
     */
    public function myAlreadyBegin(){
        $return = (new CheckModel())->myAlreadyBegin($this->user_id,I('get.'));
        $this->assign('check_list' , $return[0]);
        $this->assign('pageBar', $return[1]);
        $this->display();
    }

}