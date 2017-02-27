<?php
/**
 * 数据中心
 * User: Bao
 * Date: 2016/8/8
 * Time: 10:31
 */
namespace Home\Controller;
use Home\Controller\BaseController;
use Home\Logic\DataCenterLogic;

class DataCenterController extends BaseController
{
    public function index()
    {
        $this->date = date("Y-m-d");
        $this->display();
    }

    public function indexTable(){
        $DataCenterLogic = (new DataCenterLogic());
        $current_data = $DataCenterLogic->count($this->user_id,strtotime(date("Y-m-d")),time());//获取实时数据
        $data = $this->getSaleTask(array('user_id'=>$this->user_id));//获取总记录
        if($data['errNum'] == '0' && $data){
            $this->assign('data',$data['retData']['data']);
        }
        $this->assign('current_data',$current_data);
        $this->display();
    }

    /**
     * 获取该业务员的sale_task数据
     * @param $data array(传值参数 user_id
     * ‘user_id => session('user_id)’
     * )
     * @return 返回值 当前时间没有过期取缓存 否则获取report接口数据
     */
    private function getSaleTask($data){
        if(!isset($data)){
            return false;
        }
        $saleTaskByUser = S('saleTaskByUser');
        $time = strtotime(date("Y-m-d"));
        $key = 'sale'.$data['user_id'];
        if($saleTaskByUser[$key] && $saleTaskByUser[$key]['time'] == $time){
            return $saleTaskByUser[$key]['data'];
        }else{
            $getSaleTask = post_url(C('REPORT_getSaleTask'),$data);//推送到数据源
            $getSaleTask=json_decode($getSaleTask,true);
            $saleTaskByUser[$key] = array('data'=>$getSaleTask,'time'=>$time);
            S('saleTaskByUser',$saleTaskByUser);
            return $saleTaskByUser[$key]['data'];
        }
    }
}