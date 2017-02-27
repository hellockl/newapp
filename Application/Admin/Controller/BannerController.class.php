<?php
namespace Admin\Controller;

class BannerController extends CommonController
{
    protected $banner_model;
    
    public function __construct()
    {
        parent::__construct();
        /* @var $admin_user_model \Admin\Model\AdminUserModel */
        $banner_model = D('Banner');

        $this->banner_model = $banner_model;
    }
    
    /**
     * @description:用户列表
     * @author wuyanwen(2016年12月1日)
     */
    public function index()
    {
        //var_dump(111);exit;
        $banner_list = $this->banner_model->selectAllbanner(1);

        $this->assign('list',$banner_list['list']);
        $this->assign('page',$banner_list['page']);
        $this->display();
    }

    /**
     * @description:添加菜单
     * @author wuyanwen(2016年12月1日)
     */
    public function addBanner()
    {
        if(IS_POST){
            $data = I('post.');

            $data['create_time'] = time();
           // var_dump($data);exit;
            if($this->banner_model->addBanner($data)){
                $this->ajaxSuccess("Banner添加成功");
            }else{
                $this->ajaxError("Banner添加失败");
            }
        }else{

            $this->display();
        }
    }

    /**
     * @description:更新菜单
     * @author wuyanwen(2016年12月1日)
     */
    public function editBanner()
    {
        if(IS_POST){
            $data = I('post.');
            $data_info = array(
                'id'=>$data['id'],
                'banner_name'=>$data['banner_name'],
                'banner_img'=>$data['banner_img']
            );
            if($this->banner_model->editBanner($data_info) !== false){
                $this->ajaxSuccess('更新成功');
            }else{
                $this->ajaxError('更新失败');
            }
        }else{
            $id = I('get.id','','intval');
            $banner_info = $this->banner_model->findBannerById($id);
            $this->assign("banner_info",$banner_info);
            $this->display();
        }
    }

    public function deleteBanner(){
        $id = I('post.id','','intval');



        if(!$this->banner_model->deleteBannerById($id)){
            $this->ajaxError('删除失败');
        }else{
            $this->ajaxSuccess('删除成功');
        }
    }

    public function upload()
    {
        if(IS_POST){
            $img = $_FILES['file'];

            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   = 3145728 ;// 设置附件上传大小
            $upload->exts      = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  = './Public/'; // 设置附件上传根目录
            $upload->savePath  = 'upload/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->uploadOne($img);


            if(!$info) {// 上传错误提示错误信息
                echo json_encode(array('status' => 'error','msg' => $upload->getError()));
                exit;
            }else{// 上传成功

                $imgpath = $info['savepath'].$info['savename'];
                echo json_encode(array('status' => 'success','url'=>'/Public/'.$imgpath));
                exit;
            }

        }else{
            $this->display();
        }

    }
    

}