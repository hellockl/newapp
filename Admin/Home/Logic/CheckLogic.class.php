<?php
/**
 * Created by PhpStorm.
 * User: ZengZhiQiang
 * Date: 2016/08/08
 * Time: 14:04
 */

namespace Home\Logic;
use Think\Model;
class CheckLogic extends Model
{

    /**
     * 找出最高级别的角色
     * @author: <dingwenzai@fangjinsuo.com>
     * @date: 2016-08-5
     * @param $myRole_arr
     * @return int|string
     */
    public function myBestRoleKey($myRole_arr){
        //循环出自己最高级的角色
        $role_name='';
        foreach(C('ALLUSER_ROLE') as $value){
            if(in_array($value,$myRole_arr)){$role_name=$value;}
        }
        //找出我当前所在的位置
        $myKey=0;
        foreach(C('ALLUSER_ROLE') as $key=>$value){
            if($role_name==$value){
                $myKey=$key;
            }
        }
        return $myKey;
    }

}