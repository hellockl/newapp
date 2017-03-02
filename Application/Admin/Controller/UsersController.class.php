<?php
namespace Admin\Controller;

class UsersController extends CommonController
{
    protected $users_model;
    
    public function __construct()
    {
        //用户状态；0：待激活；1：已激活,未审核;2：已激活,已审核；3：已禁用
        parent::__construct();
        /* @var $admin_user_model \Admin\Model\AdminUserModel */
        $users_model = D('Users');

        $this->users_model = $users_model;
    }
    
    /**
     * @description:用户列表
     * @author wuyanwen(2016年12月1日)
     */
    public function index()
    {
        $users_list = $this->users_model->selectAllUsers(8);
        foreach($users_list['list'] as $key=>$val){
            $users_list['list'][$key]['father_name'] = $this->users_model->findUsersNameById($val['father_id']);
            $users_list['list'][$key]['grand_name'] = $this->users_model->findUsersNameById($val['grand_id']);
            if($val['status']==0){
                $users_list['list'][$key]['status_name'] = '<span style="color: red">未激活</span>';
            }else if($val['status']==1){
                $users_list['list'][$key]['status_name'] = '<span style="color: #00b7ee">已激活-未审核</span>';
            }else if($val['status']==2){
                $users_list['list'][$key]['status_name'] = '<span style="color: #e99f1d">已激活，已审核</span>';
            }else if($val['status']==3){
                $users_list['list'][$key]['status_name'] = '<span style="color: #4b646f">已禁用</span>';
            }
        }
        $this->assign('users_list',$users_list['list']);
        $this->assign('page',$users_list['page']);
        $this->display();
    }

    /**
     * @description:我推的用户
     * @author ckl(2017年3月1日)
     *
     */
    public function myChild(){
        $user_id = I('user_id',0,'intval');
        $where['father_id'] = $user_id;
        $mychild_list = M("Users")->where($where)->select();
        foreach($mychild_list as $key=>$val){
            if($val['status']==0){
                $mychild_list[$key]['status_name'] = '<span>未激活</span>';
            }else if($val['status']==1){
                $mychild_list[$key]['status_name'] = '<span>已激活，未审核</span>';
            }else if($val['status']==2){
                $mychild_list[$key]['status_name'] = '<span>已激活，已审核</span>';
            }else if($val['status']==3){
                $mychild_list[$key]['status_name'] = '<span>已禁用</span>';
            }
        }
        //var_dump($mychild_list,$user_id);
        $this->assign('mychild_list',$mychild_list);
        $this->display();
    }


    /**
     * @description:激活用户
     * @author wuyanwen(2017年2月128日)
     */
    public function activateUser(){
        $user_id = I('post.user_id','','intval');
        $where['user_id'] = $user_id;
        $result = M('Users')->where($where)->setField('status',1);

        if($result){
            $this->ajaxSuccess("激活成功！");
        }else{
            $this->ajaxError("激活失败");
        }
    }

    /**
     * @description:激活用户
     * @author wuyanwen(2017年2月128日
     */
    public function forbidUser(){
        $user_id = I('post.user_id','','intval');

        $where['user_id'] = $user_id;
        $res = M('Users')->where($where)->getField('is_forbid');

        $is_forbid = !empty($res['is_forbid'])?0:1;

        $result = M('Users')->where($where)->setField('is_forbid',$is_forbid);

        if($result){
            $this->ajaxSuccess("操作成功！");
        }else{
            $this->ajaxError("操作失败");
        }
    }


    /**
     * @description:添加用户
     * @author wuyanwen(2016年12月1日)
     */
    public function addUser()
    {
        if(IS_POST){
            
            $user_info = array(
                'user_name'      => I('post.user_name','','trim'),
                'password'       => md5(I('post.password','','trim')),
                'lastlogin_time' => time(),
            );
            
           if($this->admin_user_model->findAdminUserByName($user_info['user_name'])){
               $this->ajaxSuccess('该用户已经被占用');
           }
           
           if($this->admin_user_model->addAdminUser($user_info)){
               $this->ajaxSuccess('添加成功');
           }else{
              $this->ajaxError('添加失败');
           }
        }else{
            $this->display();
        }
    }
    
    
    /**
     * @description:编辑用户
     * @author wuyanwen(2016年12月1日)
     */
    public function editUser()
    {            
        if(IS_POST){
            $user_info = array(
                'user_name' => I('post.user_name','','trim'),
                'id'        => I('post.id','','intval'),
            );
           
           if(I('post.password')){
               $user_info['password'] = md5(I('post.password','','trim'));
           }

           if($this->admin_user_model->editAdminUser($user_info) !== false){
               $this->ajaxSuccess('更新成功');
           }else{
              $this->ajaxError('更新失败');
           }
        }else{
            $user_id = I('get.user_id','','intval');
            $user_info = $this->admin_user_model->findAdminUserById($user_id);
            $this->assign('user_info',$user_info);
            $this->display();
        }
    }
    
    
    /**
     * @description:删除用户
     * @author wuyanwen(2016年12月1日)
     */
    public function deleteUser()
    {
        $user_id = I('post.user_id','','intval');
        
        $result = $this->admin_user_model->deleteAdminUser($user_id);
        
        if($result){
            $this->ajaxSuccess("删除成功");
        }else{
            $this->ajaxError("删除失败");
        }
    }

    /**
     * @description ：审核用户
     *
     *
     */
    public function checkUsers(){
        if(IS_POST){
            $user_info = array(
                'status' => I('post.status','','intval'),
                'user_id'        => I('post.user_id','','intval'),
            );
            $resoult = $this->users_model->findUsersById($user_info['user_id']);
            if($resoult['status']==0){
                $this->ajaxError('该用户还未激活，请先激活！');
            }
            if($this->users_model->editUsers($user_info) !== false){
                $this->ajaxSuccess('审核成功');
            }else{
                $this->ajaxError('审核失败');
            }
        }else{
            $user_id = I('get.user_id','','intval');
            $user_info = $this->users_model->findUsersById($user_id);
            $this->assign('user_info',$user_info);
            $this->display();
        }
    }

}