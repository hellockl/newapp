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
        $users_list = $this->users_model->selectAllUsers(1);

        $this->assign('users_list',$users_list['list']);
        $this->assign('page',$users_list['page']);
        $this->display();
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
                'id'        => I('post.id','','intval'),
            );


            if($this->users_model->editUsers($user_info) !== false){
                $this->ajaxSuccess('更新成功');
            }else{
                $this->ajaxError('更新失败');
            }
        }else{
            $user_id = I('get.user_id','','intval');
            $user_info = $this->users_model->findUsersById($user_id);
            $this->assign('user_info',$user_info);
            $this->display();
        }
    }

}