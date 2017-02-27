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
                $user_data = array(
                    'user_name' => I('post.user_name','','trim'),
                    'phone' => I('post.phone','','trim'),
                    'name' => '系统管理员',
                    'password' => Md5('123456'),
                    'status'=> 2,//已激活，已审核
                    'amount_password'=>Md('654321')
                );
                $res = D('Users')->addUsers($user_data);
            }
        }else{
            $help_id = I('post.id','','intval');
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