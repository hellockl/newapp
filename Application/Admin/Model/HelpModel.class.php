<?php
namespace Admin\Model;

class HelpModel extends BaseModel
{
    protected $tableName = 'givehelp';
    

    
    /**
     * @description:每页显示数目
     * @author wuyanwen(2016年12月1日)
     * @param unknown $num
     * @return multitype:unknown string
     */
    public function selectAllGivehelp($num=10)
    {
        $where = '1=1';
    
        $count      = $this->where($where)->count();
        $page       = new \Think\Page($count,$num);
        $show       = $page->show();
        $list       = $this->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        //echo $this->getLastSql();
        return array('page' => $show , 'list' => $list);
    
    }
    
    /**
     * @description:添加后台用户
     * @author wuyanwen(2016年12月1日)
     * @param unknown $data
     * @return boolean
     */
    public function addUsers($data)
    {
        return $this->add($data) ? true : false;
    }
    
    /**
     * @description:更新用户信息
     * @author wuyanwen(2016年12月1日)
     * @param unknown $data
     */
    public function editUsers($data)
    {
        $where = array(
            'id'    => $data['id'],
        );

        unset($data['id']);
        
        return $this->where($where)->save($data);
    }
    
    /**
     * @description:删除用户
     * @author wuyanwen(2016年12月1日)
     * @param unknown $user_id
     * @return Ambigous <boolean, unknown>
     */
    public function deleteUsers($user_id)
    {
        $where = array(
            'id' => $user_id,            
        );
        
        $data = array(
            'status' => parent::DEL_STATUS,
        );
        
        return $this->where($where)->save($data);
    }
    
    
    /**
     * @description:根据id查询用户
     * @author wuyanwen(2016年12月1日)
     * @param unknown $user_id
     */
    public function findUsersById($user_id)
    {
        $where = array(
            'id'     => $user_id,
            'status' => parent::NORMAL_STATUS,
        );
        
        return $this->where($where)->find();
    }
    
    public function findUsersByName($user_name)
    {
        $where = array(
            'user_name' => $user_name,
            'status'    => parent::NORMAL_STATUS,
        );
        
        
        return $this->where($where)->find();
    }
}