<?php
namespace Admin\Controller;

class HelpController extends CommonController
{
    protected $givehelp_model;

    public function __construct()
    {
        //status = 0：待匹配；1：匹配完成，未付款；2：已打款；3：确定已打款，已完成
        parent::__construct();
        /* @var $admin_user_model \Admin\Model\AdminUserModel */
        $givehelp_model = D('Help');

        $this->givehelp_model = $givehelp_model;
    }

    /**
     * @description:用户列表
     * @author wuyanwen(2016年12月1日)
     */
    public function index()
    {
        $givehelp_list = $this->givehelp_model->selectAllGivehelp(10);
        foreach($givehelp_list['list'] as $k=>$v){
            //0：未匹配；1：已匹配，未支付；2：已匹配，已支付
            if($v['status']==0){
                $givehelp_list['list'][$k]['status_name'] = '未匹配';
            }else if($v['status']==1){
                $givehelp_list['list'][$k]['status_name'] = '已匹配，未支付';
            }else if($v['status']){
                $givehelp_list['list'][$k]['status_name'] = '已匹配，已支付';
            }else if($v['status']){
                $givehelp_list['list'][$k]['status_name'] = '已确定打款';
            }else{
                $givehelp_list['list'][$k]['status_name'] = '已完成';
            }

        }
        $this->assign('givehelp_list',$givehelp_list['list']);
        $this->assign('page',$givehelp_list['page']);
        $this->display();
    }


    /**
     * @description: 匹配帮助
     *
     */
    public function matching(){
        if(IS_POST){
            //需要创建新客户
            if(!empty(I('post.user_name','','trim'))&&!empty(I('post.phone','','trim'))){
                $isexit = D('Users')->findUsersByName(I('post.user_name'));
                if($isexit){
                    parent::ajaxError('用户名已存在，请重新输入!');
                }
                $user_data = array(
                    'user_name' => I('post.user_name','','trim'),
                    'phone' => I('post.phone','','trim'),
                    'name' => I('post.name','','trim'),
                    'password' => Md5('123456'),
                    'bank'=>I('post.bank','','trim'),
                    'bank_card'=>I('post.bank_card','','trim'),
                    'alipay'=>I('post.alipay','','trim'),
                    'wechat'=>I('post.wechat','','trim'),
                    'idcard'=>I('post.idcard','','trim'),
                    'status'=> 2,//已激活，已审核
                    'amount_password'=>Md5('654321'),
                    'create_time'=>time()
                );
                $user_id = D('Users')->addUsers($user_data);

                if(!$user_id){
                    parent::ajaxError('添加用户失败!');
                }
                //创建一个得到帮助
                $help_data = array(
                    "user_id" => $user_id,
                    "amount" => I('post.amount',0,'intval'),
                    "create_time" => time()
                );
                $add_gethelp = D('Help')->addGetHelp($help_data);
                if(!$add_gethelp){
                    parent::ajaxError('匹配失败!');
                }
            }

            $gethelp_ids = $_POST['gethelp_id'];

            if(!empty($add_gethelp)){
                $gethelp_ids[] =$add_gethelp;
            }

            if(empty($gethelp_ids)){
                parent::ajaxError('请选择用户!');
            }
            $gethelp_model = M('Gethelp');
            $givehelp_id = I('post.givehelp_id',0,'intval');
            $givehelp_uid = I('post.givehelp_uid',0,'intval');
            if(empty($givehelp_id)){
                parent::ajaxError('匹配失败 !');
            }
            foreach($gethelp_ids as $v){
                $data_a = array(
                    'givehelp_id' => $givehelp_id,
                    'givehelp_uid' => $givehelp_uid,
                    'status' => 1,
                );
                $gethelp_model->where("id=".$v)->save($data_a);
                //echo $gethelp_model->getLastSql();
            }
            $res_b = M('Givehelp')->where("id=".$givehelp_id)->setField('status',1);
            if($res_b){
                parent::ajaxSuccess('分配成功!');
            }else{
                parent::ajaxError('匹配失败 !');
            }

        }else{
            $givehelp_id = I('givehelp_id',0,'intval');
            $givehelp_info = M('Givehelp')->where('id='.$givehelp_id)->find();

            $where['G.status'] = 0; //0：未匹配；1：已匹配，未支付；2：已匹配，已支付
            $gethelp_list = M('Gethelp')->field('G.*,U.user_name')->alias('G')->join('LEFT JOIN '.C('DB_PREFIX').'users U on(U.user_id=G.user_id)')->where($where)->select();
            $this->assign('gethelp_list',$gethelp_list);
            $this->assign('givehelp_id',$givehelp_id);
            $this->assign('givehelp_uid',$givehelp_info['user_id']);
            $this->assign('givehelp_amount',$givehelp_info['amount']);
            $this->display();
        }


    }

    /**
     * @description ：匹配列表
     *
     *
     */
    public function matchList(){
        $givehelp_id = I('givehelp_id',0,'intval');
        $where['givehelp_id'] = $givehelp_id;
        $match_list = D("Help")->getMatchListById($givehelp_id);
        foreach($match_list as $key=>$val){
            if($val['status']==0){
                $match_list[$key]['status_name'] = "未匹配";
            }else if($val['status']==1){
                $match_list[$key]['status_name'] = "已匹配,未打款";
            }else if($val['status']==2){
                $match_list[$key]['status_name'] = "已匹配,已打款";
            }

        }
        //var_dump($match_list);
        $this->assign('givehelp_id',$givehelp_id);
        $this->assign('match_list',$match_list);
        $this->display();
    }


    /**
     * @description ：确认提供帮助已全都打款
     */
    public function confirmMoney(){
        $givehelp_id = I('givehelp_id',0,'intval');
        $where['id'] = $givehelp_id;
        $givehelp_modle = M("Givehelp");
        $result = $givehelp_modle->where($where)->getField('status');
        if($result['status']==0){
            $this->ajaxError('该帮助还未匹配，请先匹配');
        }
        if($result['status']==1){
            $this->ajaxError('还有匹配的用户没有确认收到款，请去确认');
        }
        $res = M("Givehelp")->where($where)->setField('status',3);
        if($res){
            $this->ajaxSuccess('操作成功');
        }else{
            $this->ajaxError('操作失败');
        }
    }

    /**
     * @description ：确认所有都打款
     */
    public function confirmAllAccept(){
        $givehelp_id = I('givehelp_id',0,'intval');
        $where['id'] = $givehelp_id;
        $givehelp_modle = M("Givehelp");
        $result = $givehelp_modle->where($where)->getField('status');
        if($result['status']==0){
            $this->ajaxError('该帮助还未匹配，请先匹配');
        }
        $resa = M("Gethelp")->where('givehelp_id='.$givehelp_id)->setField('status',2);
        $resb = M("Givehelp")->where($where)->setField('status',2);
        if($resa && $resb){
            $this->ajaxSuccess('操作成功');
        }else{
            $this->ajaxError('操作失败');
        }
    }

    /**
     * @description ：确认得到帮助已得到款
     */
    public function confirmAccept(){
        $gethelp_id = I('gethelp_id',0,'intval');
        $where['id'] = $gethelp_id;
        $gethelp_modle = M("Gethelp");
        $result = $gethelp_modle->where($where)->getField('status');
        if($result['status']==0){
            $this->ajaxError('该帮助还未匹配，请先匹配');
        }
        $res = M("Gethelp")->where($where)->setField('status',2);
        if($res){
            $this->ajaxSuccess('操作成功');
        }else{
            $this->ajaxError('操作失败');
        }
    }






    /**
     * @description ：审核用户
     *
     *
     */
    public function checkgivehelp(){
        if(IS_POST){
            $user_info = array(
                'status' => I('post.status','','intval'),
                'id'        => I('post.id','','intval'),
            );


            if($this->givehelp_model->editgivehelp($user_info) !== false){
                $this->ajaxSuccess('更新成功');
            }else{
                $this->ajaxError('更新失败');
            }
        }else{
            $user_id = I('get.user_id','','intval');
            $user_info = $this->givehelp_model->findgivehelpById($user_id);
            $this->assign('user_info',$user_info);
            $this->display();
        }
    }

}