<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends Controller {
    
    
    public function newslist(){
        $News = M('News');
        
        $count = $News->where()->count(); // 查询满足要求的总记录数
        $Page = new \Think\Page($count, 1);
        $show = $Page->show(); // 分页显示输出
        $newslist = $News->where()->limit($Page->firstRow . ',' . $Page->listRows)->order("create_time desc")->select();
        $this->assign("show", $show);
        $this->assign('newslist',$newslist);
        $this->display();
    }
    
    public function news(){
        $news_info = M("News")->where($where)->find();
        $this->assign("news_info",$news_info);
        $this->display();
    }

}