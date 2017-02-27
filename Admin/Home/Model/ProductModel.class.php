<?php
/**
 * Created by PhpStorm.
 * User: zengzhiqiang
 * Date: 2016/10/11
 * Time: 11:06
 */

namespace Home\Model;
use Think\Model;

class ProductModel extends Model
{
    /**
     * 产品列表
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param array $get
     */
    public function productList($get)
    {
        if($get['sub_company_id']){$where['sub_company_id']=array('eq',$get['sub_company_id']);}
        if($get['agency_name']){$where['agency_name']=array('like','%'.$get['agency_name'].'%');}
        if($get['product_name']){$where['product_name']=array('like','%'.$get['product_name'].'%');}
        if($get['agency_owner']){$where['agency_owner']=array('like','%'.$get['agency_owner'].'%');}
        if($get['owner']){$where['owner']=array('like','%'.$get['owner'].'%');}
        if(isset($get['status'])&&($get['status']!='')){$where['status']=array('eq',$get['status']);}

        if($get['condition']&&$get['value']){
            if($get['condition'] == 'sub_company'){
                $where['sub_company_id'] = $get['value'];
            }elseif($get['condition'] == 'name'){
                $where_a['agency_name'] = array('like','%'.$get['value'].'%');
                $where_a['product_name'] = array('like','%'.$get['value'].'%');
                $where_a['_logic'] = 'or';
                $where['_complex'] = $where_a;
            }
        }

        $list = M('Product')->where($where)->select();
        $count = count($list);
        $Page = new \Think\Page($count,10);
        $p = $get['p'] ? $get['p'] : 1 ;        //获得页码
        $list =  M('Product')->where($where)->page($p,10)->order('create_time DESC')->select();
        $sub_company_id = array_column($list,'sub_company_id');
        $where_d['department_id'] = array('in',$sub_company_id);
        $arr = array("where"=>serialize($where_d),"type"=>2);
        $department_list = json_decode(post_url(C('BASE_getDepartmentByWhere'),$arr),true);
        foreach ($department_list['retData'] as $k=>$v){
            $department_name[$v['department_id']] = $v['name'];
        }
        foreach ($list as $k=>$v){
            $list[$k]['sub_company'] = $department_name[$v['sub_company_id']];
        }
        $return[0] = $list;                     //员工列表
        $return[1] = $Page->show();             //分页信息
        $return[2] = $count;             //分页信息
        return $return;
    }
    /**
     * 添加产品
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param array $get
     */
    public function add($post)
    {
        $post['create_role_id'] = $_SESSION['user_info']['user_id'];
        $post['create_time'] = time();
        $post['update_time'] = time();
        if(M('Product')->add($post)){
            $result['errNum'] = 0;
            $result['errMsg'] = '添加成功';
        }else{
            $result['errNum'] = 1;
            $result['errMsg'] = '添加失败';
        }
        return $result;
    }
    /**
     * 添加产品
     * @author: <zengzhiqiang@fangjinsuo.com>
     * @date: 2016-10-11
     * @param array $get
     */
    public function edit($post)
    {
        $post['update_time'] = time();
        if(M('Product')->save($post)){
            $result['errNum'] = 0;
            $result['errMsg'] = '编辑成功';
        }else{
            $result['errNum'] = 1;
            $result['errMsg'] = '编辑失败';
        }
        return $result;
    }
    public function productInfo($product_id = 0){
        if($product_id == 0){
            return false;
        }
        $product_info = M('Product')->find($product_id);
        if($product_info){
            $where['department_id'] =$product_info['sub_company_id'];
            $arr = array("where"=>serialize($where),"type"=>1);
            $department_list = json_decode(post_url(C('BASE_getDepartmentByWhere'),$arr),true);
            $product_info['sub_company'] = $department_list['retData']['name'];
        }
        return $product_info;
    }
    public function getSubCompany()
    {
        $where['department_type'] = 'sub_company';
        $where['status'] = 0;
        $arr = array("where"=>serialize($where),"type"=>2);
        $return = json_decode(post_url(C('BASE_getDepartmentByWhere'),$arr),true);
        $result = $return['retData'];
        return $result;
    }
    public function getProductList($sub_company_id = 0){
        if($sub_company_id == 0){
            $product_list = M('Product')->select();
        }else{
            $product_list = M('Product')->where('sub_company_id = %d',$sub_company_id)->select();
        }
        return $product_list;
    }

    public function addOrSave($post=array()){
        $post['update_time']=time();
        if($post['product_id']){
            return $this->save($post)?true:false;
        }else{
            $post['create_time']=time();
            return $this->add($post)?true:false;
        }
    }

    public function findByWhere($where){
        return $this->where($where)->find();
    }

    public function selectByWhere($where){
        return $this->where($where)->select();
    }


    public function getProductByContract($contractInfo){
        //如果是渠道类型,取出渠道产品的名字
        if($contractInfo['contract_type']==1 && !empty($contractInfo['product_name'])){
            $where['product_id']=array('in',$contractInfo['product_name']);
            $product_names_arr=array_column($this->selectByWhere($where),'product_name');
            return implode(',',$product_names_arr);
        }else{
            return '';
        }
    }
}