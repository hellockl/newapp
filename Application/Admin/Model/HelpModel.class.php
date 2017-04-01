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

        $count      = $this->alias('G')->where($where)->count();
        $page       = new \Think\Page($count,$num);
        $show       = $page->show();
        $list       = $this->alias('G')->field('G.*,U.user_name,U.name,U.phone')->join("LEFT JOIN ".C('DB_PREFIX')."users U on(U.user_id=G.user_id)")->where($where)->limit($page->firstRow.','.$page->listRows)->order('G.create_time desc')->select();
        //echo $this->getLastSql();
        return array('page' => $show , 'list' => $list);

    }

    /**
     * @description:添加得到帮助
     * @author wuyanwen(2016年12月1日)
     * @param unknown $data
     * @return boolean
     */
    public function addGetHelp($data)
    {
        return M('Gethelp')->add($data);
    }

    /**
     * @description:添加匹配列表
     * @author wuyanwen(2017年3月1日)
     * @param unknown $data
     * @return boolean
     */
    public function getMatchListById($givehelp_id){
        $where['G.givehelp_id'] = $givehelp_id;
        $list = M('Gethelp')->alias("G")->field("G.*,U.user_name,U.alipay,U.wechat,U.name,U.phone")->join("LEFT JOIN ".C('DB_PREFIX')."users U on(U.user_id=G.user_id)")->where($where)->select();
        return $list;
    }


}