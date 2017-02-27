<?php
namespace Admin\Model;

use Think\Model;

class BannerModel extends Model
{
    const DEL_STATUS = 3;
    function addBanner($data){
        return $this->add($data) ? true : false;
    }

    public function selectAllBanner($num=10)
    {
        $where = array(
            'status' => 1,
        );

        $count      = $this->where($where)->count();
        $page       = new \Think\Page($count,$num);
        $show       = $page->show();
        $list       = $this->where($where)->limit($page->firstRow.','.$page->listRows)->select();

        return array('page' => $show , 'list' => $list);

    }

    public function editBanner($data){
        $where = array(
            'id'    => $data['id'],
        );

        unset($data['id']);

        return $this->where($where)->save($data);
    }

    public function findBannerById($id){
        $where = array(
            'id'     => $id,
        );
        return $this->where($where)->find();
    }

    public function deleteBannerById($id){
        $where = array(
            'id' => $id,
        );

        $data = array(
            'status' => self::DEL_STATUS,
        );

        return $this->where($where)->save($data);
    }
}