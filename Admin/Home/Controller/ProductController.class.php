<?php
/**
 * Created by PhpStorm.
 * User: zengzhiqiang
 * Date: 2016/10/11
 * Time: 11:06
 */

namespace Home\Controller;

use Home\Model\ProductModel;

class ProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 产品管理
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     */
    public function index()
    {
        $this->assign('sub_company_list',D('Product')->getSubCompany());
        $this->display();
    }
    /**
     * 产品列表
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param get方式-查询条件
     */
    public function table()
    {
        $result = (new ProductModel())->productList(I('get.'));
        $this->assign('product_list', $result[0]);
        $this->assign('pageBar', $result[1]);
        $this->display();
    }

    /**
     * 产品列表
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param get方式-查询条件
     */
    public function add()
    {
        if(IS_POST){
            $post=I('post.');
            $post['cooperate_time']=strtotime($post['cooperate_time']);
            $return = D('Product')->add($post);
            $this->ajaxReturn($return);
        }else{
            $this->assign('sub_company_list',D('Product')->getSubCompany());
            $this->display();
        }
    }

    /**
     * 产品列表
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param get方式-查询条件
     */
    public function edit()
    {
        if(IS_POST){
            $post=I('post.');
            $post['cooperate_time']=strtotime($post['cooperate_time']);
            $return = D('Product')->edit($post);
            $this->ajaxReturn($return);
        }else{
            $this->assign('sub_company_list',D('Product')->getSubCompany());
            $return = D('Product')->productInfo(I('get.product_id'));
            $this->assign('product_info', $return);
            $this->display();
        }
    }

    /**
     * 产品列表
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param get方式-查询条件
     */
    public function view()
    {
        $return = D('Product')->productInfo(I('get.product_id'));

        $where['department_id'] = $return['sub_company_id'];
        $arr = array("where"=>serialize($where),"type"=>1);
        $result = json_decode(post_url(C('BASE_getDepartmentByWhere'),$arr),true);

        $return['sub_company'] = $result['retData']['name'];
        $this->assign('product_info', $return);
        $this->display();
    }

    /**
     * 产品列表
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param get方式-查询条件
     */
    public function productSelect(){
        $get=I('get.');
        $return = D('Product')->productList($get);
        $this->assign('ProductList', $return[0]);
        $count = $return[2];
        $total = $count%10 ? intval($count/10)+1 : intval($count/10);
        $this->assign('count',$count);
        $this->assign('total',$total);
        $this->assign('pageBar', $return[1]);
        $this->display();
    }
    public function productSelectChange(){
        $get=I('get.');
        $return = D('Product')->productList($get);
        $data['list'] = $return[0];
        $data['p'] = I('get.p') ? I('get.p') : 1;
        $data['count'] = $return[2];
        $data['total'] = $data['count']%10 ? intval($data['count']/10)+1 : $data['count']/10;
        $this->ajaxReturn(array('status'=>0,'info'=>'','data'=>$data));
    }
    public function getSubCompany(){
        $sub_company_list = D('Product')->getSubCompany();
        if($sub_company_list){
            $data['status']  = 0;
            $data['info']  = '获取分公司成功';
            $data['data'] = $sub_company_list;
        }else{
            $data['status']  = 1;
            $data['info']  = '获取分公司失败';
            $data['data'] = '';
        }
        $this->ajaxReturn($data);
    }

    public function updataStatus($id,$toStatus){
        $productInfo=(new ProductModel())->findByWhere(array('product_id'=>$id));
        if($productInfo){
            if($productInfo['status']==$toStatus){$this->errorjsonReturn('操作错误');}
            if((new ProductModel())->addOrSave(array('product_id'=>$id,'status'=>$toStatus))){
                $this->setjsonReturn('操作成功');
            }else{
                $this->errorjsonReturn('操作失败');
            }
        }else{
            $this->errorjsonReturn('操作错误');
        }
    }
}