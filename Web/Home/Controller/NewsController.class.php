<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends Controller {

    public function newslist(){
        $this->assign('nav',"news");
        $News = M('News');
        
        $count = $News->where()->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, 1);
        $show = $Page->show(); // 分页显示输出
        $newslist = $News->where()->limit($Page->firstRow . ',' . $Page->listRows)->order("create_time desc")->select();
        $this->assign("show", $show);
        $this->assign('newslist',$newslist);
        $this->display();
    }
    
    public function getNewsDetail(){
        $id = I("post.id",0,'intval');
        $where['id']=$id;
        $news_info = M("News")->where($where)->find();
        $news_info['create_time'] = date("Y-m-d:H:i:s");
        
        if(!empty($news_info)){
            $this->ajaxReturn(array('errorCode'=>0,'result'=>$news_info));
        }else{
            $this->ajaxReturn(array('errorCode'=>1,'errorMsg'=>'操作失败'));
        }
       
    }

}