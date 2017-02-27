<?php
namespace  Home\Controller;
use Home\Controller\BaseController;
/**
* 合同管理 模块
* @author <zengzhiqiang@fangjinsuo.com>
* @createdate: 2016-08-08
*/
class ContractController extends BaseController {
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * 合同列表
    * @author <zengzhiqiang@fangjinsuo.com>
    * @createdate: 2016-08-08
    */
    public function index(){
        $this->display();
    }

    /**
     * 自营合同表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @$users_arr下属包括自己的用户id数组
     * @param get方式-查询条件
     */
    public function companyTable(){
        $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"));
        $users=implode(',',$users_arr);

        $get=I('get.');
        $get['contract_type']=2;
        $result = D('Contract')->contractList($get,$users);

        $this->assign('myUser_id' , session("user_info.user_id"));
        $this->assign('contract_list' , $result[0]);
        $this->assign('pageBar', $result[1]);
        $this->display();
    }

    /**
     * 渠道合同表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @$users_arr下属包括自己的用户id数组
     * @param get方式-查询条件
     */
    public function channelTable(){
        $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"));
        $users=implode(',',$users_arr);

        $get=I('get.');
        $get['contract_type']=1;
        $result = D('Contract')->contractList($get,$users);

        $this->assign('myUser_id' , session("user_info.user_id"));
        $this->assign('contract_list' , $result[0]);
        $this->assign('pageBar', $result[1]);
        $this->display();
    }

    /**
     * 审批页面
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function checkList(){
        $this->display();
    }

    /**
     * 合同详细信息
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 contract_id合同的id号
     */
    public function view(){
        $contractInfo=D('Contract')->contractInfo(I('get.contract_id'));
        $agreementInfo=D('Agreement')->findByWhere(array('agreement_id'=>$contractInfo['agreement_id']));
        $this->assign('contract_info', $contractInfo);
        $this->assign('agreementInfo', $agreementInfo);
        $this->display();
    }
    /**
     * 添加合同
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @post 方式
     * @return json数据
     */
    public function add(){
        if(IS_POST){
            D('Contract')->contractAdd(I('post.'))?$this->setjsonReturn('添加合同成功'):$this->errorjsonReturn('添加失败');
        }else{
            if(I('get.agreement_number')){         //通过居间协议编号添加合同
                $agreementInfo=D('Agreement')->findByWhere(array('number'=>I('get.agreement_number')));
                if(!$agreementInfo){echo '居间协议不存在';die();}
                if($agreementInfo['status']==-1){echo '居间协议为无效,不能添加合同';die();}
                if($agreementInfo['create_user_id']!=session('user_info.user_id')){echo '您没有该协议的权限';die();}
                if(D('Contract')->getAgreementLock($agreementInfo['agreement_id'])){echo '该居间协议已签署合同';die();}
            }else{                                //通过居间协议的主键添加合同
                if(D('Contract')->getAgreementLock(I('get.id'))){echo '该居间协议已签署合同';die();}
                $agreementInfo=D('Agreement')->findByWhere(array('agreement_id'=>I('get.id')));
                if($agreementInfo['status']==-1){echo '居间协议为无效,不能添加合同';die();}
                if($agreementInfo['create_user_id']!=session('user_info.user_id')){echo '您没有该协议的权限';die();}
            }
            $this->assign('data',$agreementInfo);
            $this->display();
        }
    }
    /**
     * 编辑合同
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param post方式 编辑合同
     * @return json数据
     * @param get方式 id 根据合同的id进入界面
     */
    public function edit(){
        $this->assign('contract_id', I('get.id'));
        $this->display();
    }
    /**
     * 删除合同
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @get contract_id合同的id
     * @return json数据
     */
    public function delete(){
        D('Contract')->contractDelete(I('get.contract_id'))?$this->setjsonReturn('删除成功'):$this->errorjsonReturn('删除失败');
    }


    /**
     * 合同信息页
     */
    public function tabContractInfo(){
        $contract_id=I('post.contract_id')?I('post.contract_id'):I('get.id');

        $contractInfo=M('Contract')->where(array('contract_id'=>$contract_id))->find();
        if($contractInfo['create_user_id']!=session('user_info.user_id')){I('post.contract_id')?$this->errorjsonReturn('您没有权限'):exit('您没有权限');}
        if($contractInfo['check_process']!=0){exit('此合同状态不允许编辑');}
        if(IS_POST){
            D('Contract')->contractEdit(I('post.'))?$this->setjsonReturn('操作成功'):$this->errorjsonReturn('操作失败');
        }else{
            $contractInfo=D('Contract')->contractInfo($contract_id);
            $this->assign('contract_info', $contractInfo);
            $this->display();
        }
    }
}