<?php
namespace Admin\Controller;

class NewsController extends CommonController
{
    protected   $news_model;
    protected   $user_info;
    
    public function __construct()
    {
        parent::__construct();
        /* @var $admin_user_model \Admin\Model\AdminUserModel */
        $news_model = D('News');

        $this->user_info = session('user_info');
        $this->news_model = $news_model;
    }
    
    /**
     * @description:用户列表
     * @author wuyanwen(2016年12月1日)
     */
    public function index()
    {
        $search['title']      = I('title');
        $search['start_time'] = I('start_time');
        $search['end_time']   = I('end_time');

        $news_list = $this->news_model->selectAllNews(5,$search);
        
        $this->assign('news_list',$news_list['list']);
        $this->assign('page',$news_list['page']);
        $this->assign('search',$search);

        $this->display();
    }
    public function uploadImgForContent()
    {
        //处理file上传 这里是调用thinkphp封装好\Think\Upload这个上传类 可以学习写thinkphp官方这个类是怎么写的
        $config = array(
            'rootPath' => './Public/',
            'savePath' => 'upload/newscontent/',
            'maxSize' => 11048576,
            'saveName' => array('uniqid', ''),
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => false,
        );
        $upload = new \Think\Upload($config);//
        $info = $upload->upload();
        //开始上传
        if ($info) {
            //上传成功
            $first = array_shift($info);
            if (!empty($first['url'])) {
                $url = $first['url'];
            } else {
                $url = C('HTTP_UPLOAD').'newscontent/'.$first['savename'];
            }
            $arr['code'] = 0;
            $arr['msg'] = '';
            $arr['data']['src'] = $url;
            $arr['data']['title'] = $first['savename'];
            echo json_encode($arr);
        } else {
            $arr['code'] = 300;
            $arr['msg'] = $upload->getError();
            $arr['data']['src'] = '';
            $arr['data']['title'] = '';
            echo json_encode($arr);
        }
    }
    /*
     * @description: 新增资讯
     */
    public function addNews()
    {
        if(IS_POST) {

            $news_info = array(
                'title'         => I('post.title', '', 'trim'),
                'content'       => I('post.content'),
                'smeta'         => I('post.smeta'),
                'author'        => $this->user_info['user_name'],
                'create_time'   => time(),
                'hits'          => 0,
                'status'        => 1,
            );

//            if ($this->admin_user_model->findAdminUserByName($user_info['user_name'])) {
//                $this->ajaxSuccess('该用户已经被占用');
//            }

            if ($this->news_model->addNews($news_info)) {
                $this->ajaxSuccess('添加成功');
            } else {
                $this->ajaxError('添加失败');
            }
        }else{
            $this->display();
        }
    }

    /**
     * @description:编辑资讯
     */
    public function editNews()
    {
        if(IS_POST){
            $news_info = array(
                'id'            => I('post.id'),
                'title'         => I('post.title', '', 'trim'),
                'content'       => I('post.content'),
                'smeta'         => I('post.smeta'),
                'author'        => $this->user_info['user_name'],
                'update_time'   => time(),
                'status'        => 1,
            );

            if($this->news_model->editNews($news_info) !== false){
                $this->ajaxSuccess('更新成功');
            }else{
                $this->ajaxError('更新失败');
            }
        }else{
            $news_id = I('get.news_id','','intval');
            $news_info = $this->news_model->findNewsById($news_id);
            $this->assign('news_info',$news_info);
            $this->display();
        }
    }
    /**
     * @description:删除资讯
     * @author wuyanwen(2016年12月1日)
     */
    public function deleteNews()
    {
        $news_id = I('post.news_id','','intval');

        $result = $this->news_model->deleteNews($news_id);

        if($result){
            $this->ajaxSuccess("删除成功");
        }else{
            $this->ajaxError("删除失败");
        }
    }
}