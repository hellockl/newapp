<?php
/**
 * Created by PhpStorm.
 * User: ZengZhiQiang
 * Date: 2016/08/08
 * Time: 14:04
 */

namespace Home\Model;
use Home\Model\ContractModel as Contract;
class UserModel
{
    /**
     * 缓存用户的信息
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $user_id
     * @return mixed
     */
    public function getUserInfoByID($user_id){
        $allUser=S('allUser');
        if(!isset($allUser[$user_id])||(isset($allUser[$user_id]['get_time']) && $allUser[$user_id]['get_time'] && (time()-$allUser[$user_id]['get_time'])>C('SaveUserInfo')*60)){
            $Contract = new Contract();
            $newUserInfo=$Contract->getUserByWhere(array('user_id'=>$user_id),1);
            if(!empty($newUserInfo)){
                $newUserInfo['get_time'] = time();
                $allUser[$newUserInfo['user_id']] = $newUserInfo;
                S('allUser',$allUser);
            }
        }
        return $allUser[$user_id];
    }




    /**
     * 根据用户姓名获取user_id
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $user_id
     * @return mixed
     */
    public function getUserIdByName($name,$user_ids=array()){
        $allUser=S('allUser');
        $return_user_id = 0;
        foreach($user_ids as $value){
            $userInfo=$allUser[$value];
            if($name==$userInfo['name']){
                $return_user_id=$userInfo['user_id'];
            }
        }
        if($return_user_id){
           return $return_user_id;
        }else{
            $where['name'] = $name;
            $where['user_id'] = array('in',$user_ids);
            $user_info = D('Contract')->getUserByWhere($where,1);
            $user_info['user_id'] = $user_info['user_id'] ? $user_info['user_id'] : 0;
            return $user_info['user_id'];
        }

    }

    /**
     * 修改密码
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-10-14
     * @param $post
     * @return mixed
     */
    public function editPassword($post){
        $result =  json_decode(post_url(C('BASE_editPassword'),$post),true);
        return $result;
    }


    /**
     * 获取用户的所有角色名
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $user_id
     * @return array
     */
    public function getUserRoleNames($user_id){
        //找出合同的创建人角色基本信息
        $userInfo=$this->getUserInfoByID($user_id);
        $role_ids=explode(',',$userInfo['role_ids']);
        $roles=array();
        foreach($role_ids as $value){
            $Contract = new Contract();
            $roleInfo=$Contract->getRoleInfoBy('role_id',$value);
            $roles[]=$roleInfo['name'];
        }
        return $roles;
    }
    /**
     * 获取城市列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-10-12
     * @param int $province_id
     * @param int $city_id
     * @param int $area_id
     * @return mixed
     */
    public function getCity($province_id = 0,$city_id = 0,$area_id = 0){
        $ret_spt = json_decode(get_url('http://api.fangjinsuo.com/idcarea/listAreas'),true);
        $result = $ret_spt;
        if($ret_spt['errNum'] == 0 && $ret_spt['retData']){
            $result['errNum'] = 0;
            $result['errMsg'] = '获取城市列表成功';
            $result['retData']['province'] = '<option value="">--请选择省份--</option>';
            $result['retData']['city'] = '<option value="">--请选择城市--</option>';
            $result['retData']['area'] = '<option value="">--请选择地区--</option>';
            foreach ($ret_spt['retData'] as $k=>$v){
                if($province_id == $v['id']){
                    $result['retData']['province'] .= '<option value="'.$v['id'].'" selected >'.$v['name'].'</option>';
                    $ret_sct = json_decode(get_url('http://api.fangjinsuo.com/idcarea/listAreas/'.$province_id),true);
                    if($ret_sct['errNum'] == 0 && $ret_sct['retData']){
                        foreach ($ret_sct['retData'] as $kk=>$vv){
                            if($city_id == $vv['id']){
                                $result['retData']['city'] .= '<option value="'.$vv['id'].'" selected >'.$vv['name'].'</option>';
                                $ret_sat = json_decode(get_url('http://api.fangjinsuo.com/idcarea/listAreas/'.$city_id),true);
                                if($ret_sat['errNum'] == 0 && $ret_sat['retData']){
                                    foreach ($ret_sat['retData'] as $kkk=>$vvv){
                                        if($area_id == $vvv['id']){
                                            $result['retData']['area'] .= '<option value="'.$vvv['id'].'" selected >'.$vvv['name'].'</option>';
                                        }else{
                                            $result['retData']['area'] .= '<option value="'.$vvv['id'].'" >'.$vvv['name'].'</option>';
                                        }
                                    }
                                }
                            }else{
                                $result['retData']['city'] .= '<option value="'.$vv['id'].'" >'.$vv['name'].'</option>';
                            }
                        }
                    }
                }else{
                    $result['retData']['province'] .= '<option value="'.$v['id'].'" >'.$v['name'].'</option>';
                }
            }
        }else{
            $result['errNum'] = 1;
            $result['errMsg'] = '获取城市列表失败';
            $result['retData'] = '';
        }
        return $result;
    }
    /**
     * 员工管理模块  分公司所有员工
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-11-08
     * @return mixed
     */
    public function UserList(){
        switch ($_SESSION['user_info']['data_type']){
            case 4:
                $city_list = C("SALECITY");
                foreach($city_list as $p){
                    $data[$p] = array();
                }
                $where['sub_company_id'] = array('neq',0);
                break;
            case 3:
                $city_list[0] = substr($_SESSION['user_info']['sub_company_name'],0,6);
                $data = array($city_list[0]=>array());
                $where['sub_company_id'] = $_SESSION['user_info']['sub_company_id'];
                break;
            case 2:
                $city_list[0] = substr($_SESSION['user_info']['sub_company_name'],0,6);
                $data = array($city_list[0]=>array());
                $post_department_id['department_id'] = $_SESSION['user_info']['department_id'];
                $sub_department_id = json_decode(post_url(C('BASE_getSubDepartmentId'),$post_department_id),true);
                $where['department_id'] = array('in',$sub_department_id);
                break;
            default :
                $city_list[0] = substr($_SESSION['user_info']['sub_company_name'],0,6);
                $data = array($city_list[0]=>array());
                $where['department_id'] = $_SESSION['user_info']['department_id'];
        }

        $sales = array();
        foreach ($city_list as $v){
            $post_city['city'] = $v;
            $result = json_decode(post_url(C('BASE_getAllSalesman'),$post_city),true);
            //$sales[$v] = $result['retData'];
            if($result['retData']){
                $sales = array_merge($sales,$result['retData']);
            }
            $user_ids[$v] = RedisModel::instance()->getLRANGE('listAllocate'.$v);
        }
        if(!empty($sales)){
            $where['user_id'] = array('in',$sales);
        }
        $where['status']  = 1;
        $where = serialize($where);
        $arr = array('where'=>$where,'type'=>2);
        $return = json_decode(post_url(C('BASE_getUserInfo'),$arr),true);
        if($return['retData']){
            foreach($return['retData'] as $k=>$v){
                $city = substr($v['sub_company_name'],0,6);
                if(in_array($city,$city_list)){
                    if(in_array($v['user_id'],$user_ids[$city])){
                        $v['redis'] = 1;
                    }else{
                        $v['redis'] = 0;
                    }
                    $data[$city][$v['department_name']][] = $v;
                }
            }
        }
        return $data;
    }

    /**
     *  该分公司当前的redis业务员队列
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-11-08
     * @return mixed
     */
    public function userRedis($city = ''){
        if($city != ''){
            $user_ids = RedisModel::instance()->getLRANGE('listAllocate'.$city);
            if($user_ids){
                $where['user_id'] = array('in',$user_ids);
                $where = serialize($where);
                $arr = array('where'=>$where,'type'=>2);
                $return = json_decode(post_url(C('BASE_getUserInfo'),$arr),true);
                $data = array();
                foreach ($user_ids as $k=>$v){
                    foreach ($return['retData'] as $key=>$val){
                        if($val['user_id'] == $v){
                            $data[] = $val;
                            unset($return[$key]);
                        }
                    }

                }
                if($return['errNum'] == 0){
                    $result['errNum'] = 0;
                    $result['errMsg'] = '查询队列成功';
                    $result['retData'] = $data;
                    return $result;
                }else{
                    $result['errNum'] = 1;
                    $result['errMsg'] = '查询队列失败';
                    $result['retData'] = '';
                    return $result;
                }
            }else{
                $result['errNum'] = 1;
                $result['errMsg'] = '队列为空';
                $result['retData'] = '';
                return $result;
            }
        }else{
            $result['errNum'] = 1;
            $result['errMsg'] = '城市错误';
            $result['retData'] = '';
            return $result;
        }
    }
    /**
     *  删除该分公司的redis业务员队列中的业务员
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-11-08
     * @return mixed
     */
    public function del_redis($get = array()){
        $user_ids = RedisModel::instance()->getLRANGE('listAllocate'.$get['city']);
        $id = intval($get['id']);
        if(in_array($id,$user_ids)){
            RedisModel::instance()->delUserList('listAllocate'.$get['city'],$id);
            $user_ids2 = RedisModel::instance()->getLRANGE('listAllocate'.$get['city']);
            if(!in_array($id,$user_ids2)){
                $result['errNum'] = 0;
                $result['errMsg'] = '移除成功';
            }else{
                $result['errNum'] = 1;
                $result['errMsg'] = '移除失败';
            }
        }else{
            $result['errNum'] = 1;
            $result['errMsg'] = '该业务员不在此队列中';
        }
        return $result;
    }
    /**
     *  添加该分公司的redis业务员队列中的业务员
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-11-08
     * @return mixed
     */
    public function add_redis($get = array()){
        $id = intval($get['id']);
        if($id){
            $user_ids = RedisModel::instance()->getLRANGE('listAllocate'.$get['city']);
            if(!in_array($id,$user_ids)){
                RedisModel::instance()->setUserList('listAllocate'.$get['city'],$id);
                $user_ids = RedisModel::instance()->getLRANGE('listAllocate'.$get['city']);
                if(in_array($id,$user_ids)){
                    $result['errNum'] = 0;
                    $result['errMsg'] = '加入成功';
                }else{
                    $result['errNum'] = 1;
                    $result['errMsg'] = '加入失败';
                }
            }else{
                $result['errNum'] = 1;
                $result['errMsg'] = '该业务员已在此队列中';
            }
        }else{
            $result['errNum'] = 1;
            $result['errMsg'] = '业务员数据异常';
        }
        return $result;
    }
    /**
     *  获取员工列表table  搜索、分页
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-11-08
     * @return mixed
     */
    public function getUserTable($get = array()){
        if(intval($get['department_id'])){
            $where['department_id'] = intval($get['department_id']);
        }
        if(trim($get['name'])){
            $where['name'] = array('like','%'.trim($get['name']).'%');
        }
        if(!empty($get['user_ids'])){
            $where['user_id'] = array('in',$get['user_ids']);
        }
        $where['status'] = 1;
        $list = D('Contract')->getUserByWhere($where,2);
        $count = count($list);
        $Page = new \Think\Page($count,5);
        $p = $get['p'] ? $get['p'] : 1 ;  //获得页码
        if(!empty($list)){
            $return[0] = array_slice($list,($p-1)*5,5);   //员工列表
        }else{
            $return[0] = $list;             //员工列表
        }
        $return[1] = $Page->show();     //分页信息
        $return[2] = $count;             //分页信息
        $return[3] = $where;             //分页信息
        return $return;
    }
}