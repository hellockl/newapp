<?php

namespace Home\Model;

use Think\Model;

/**
 * Created by PhpStorm.
 * Date: 2016/8/8
 * Time: 11:30
 */
class CustomerPoolOperateLogModel extends Model
{

    /**
     * 添加或者修改数据
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $post
     * @return bool
     */
    function addOrSave($post=array()){
        $post['create_time']=time();
        if($post['id']){
            return $this->save($post)?true:false;
        }else{
            return $this->add($post)?true:false;
        }
    }

    /**
     * 根据条件查找
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param array $where
     * @return bool|mixed
     * 通过where条件 查找一条数据
     * 注意参数传值的链表查询
     */
    public function findInfoByWhere($where = array())
    {
        $result = $this->where($where)->find();
        if (!empty($result)) {
            return $result;
        }
        return false;
    }
}