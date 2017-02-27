<?php
/**
 * Created by PhpStorm.
 * User: Bao
 * Date: 2016/8/8
 * Time: 10:31
 */
namespace Home\Controller;
use Home\Controller\BaseController;
use Api\Controller\MessageController as Message;
use Home\Model\AllocateLogModel;
use Home\Model\CustomerSaleModel;
use Home\Model\RedisModel;
use Home\Model\UserModel as User;
use Home\Model\UserModel;

class CustomerController extends BaseController {

    public $limitcity = array();
    public function __construct()
    {
        parent::__construct();
        $configs=D('Contract')->getConfig('quick_city,house_amount,customer_level');
        $this->assign('house_amount',$configs['house_amount']);//几套房
        $this->assign('customer_level',$configs['customer_level']);//客户等级
        foreach(C("SERVICECITY") as $q){
            $this->limitcity[] = $q."市";
        }
        $this->assign('quick_city',$this->limitcity);//快速选择城市

    }
    /**
     * 我的客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function index(){
        $customer_source = D("customerSale")->customerSourceByGroup();
        $this->assign('customer_source',$customer_source);//客户来源
        $this->assign('customer_classify_SET',C('customer_classify'));
        $this->display();
    }
    /**
     * 个人客户列表
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 p页码
     */
    public function indexTable(){
        $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"));
        $users=implode(',',$users_arr);
        $p =I("get.p")? I("get.p"):1;
        $data = D("CustomerSale")->getList('self',$users,I("get."),$p);
        $this->assign("data",$data);
        $this->display();
    }


    /**
     * 仅仅获取自己的客户界面
     * @author <jie@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function selCustomer(){
        $this->display();
    }

    /**
     * 仅仅获取自己的客户表
     * @author <jie@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function selCustomerTable(){
        //仅仅获取自己的客户
        $users=session("user_info.user_id");

        $p =I("get.p")? I("get.p"):1;
        $data = D("CustomerSale")->getList('self',$users,I("get."),$p,'', 5);
        $this->assign("data",$data);
        $this->assign("totalRows",$data['oPage']->totalRows);
        $this->assign("totalPages",$data['oPage']->totalPages);
        if($p <= 0){
            $p = 1;
        }elseif($p >= $data['oPage']->totalPages){
            $p = $data['oPage']->totalPages;
        }
        $this->assign("p",$p);
        $this->display();
    }
    /**
     * 本地公盘
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function localPool(){
        $customer_source = D("customerSale")->customerSourceByGroup();
        $this->assign('customer_source',$customer_source);//客户来源
        $this->assign('customer_classify_SET',C('customer_classify'));
        $this->display();
    }
    /**
     * 本地公盘池列表页
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 搜索条件
     */
    public function localTable(){
        $p =I("get.p")? I("get.p"):1;
        //下面获取自己所在的城市
        $myCity=D('Contract')->getCityByUserid($this->user_id);
        $data = D("CustomerSale")->getList('local',session("user_info.user_id"),I("get."),$p,$myCity);

        $this->assign("data",$data);
        $this->display();

    }
    /**
     * 外地公盘
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function otherPool(){
        $customer_source = D("customerSale")->customerSourceByGroup();
        $this->assign('customer_source',$customer_source);//客户来源
        $this->assign('customer_classify_SET',C('customer_classify'));
        $this->display();
    }
    /**
     * 外地池列表页
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 p页码
     * @param get方式 param 搜索条件
     */
    public function otherTable(){
        $p =I("get.p")? I("get.p"):1;
        //下面获取自己所在的城市
        $myCity=D('Contract')->getCityByUserid($this->user_id);
        $data = D("CustomerSale")->getList('other',session("user_info.user_id"),I("get."),$p,$myCity);

        $this->assign("data",$data);
        $this->display();
    }

    /**
     * 移除客户到公盘
     */
    public function removeCustomer($customer_id){
        $customerInfo = D("CustomerSale")->findCustomerByWhere(array('customer_id'=>I("get.customer_id")));
        //判断状态允不允许移除
        if($customerInfo['customer_type']!='意向客户'){$this->errorjsonReturn('此客户不允许被扔回到公盘!');}
        //如果不是自己的客户,看看是不是自己下属的客户
        if($customerInfo['owner_user_id']!=$this->user_id){
            $this->errorjsonReturn('你没有权限扔回此客户!');
        }
        //下面开始移除客户
        $res = (new CustomerSaleModel())->addOrSave(array('customer_id'=>$customer_id,'owner_user_id'=>0,'owner_user_name'=>''),'扔回公盘');
        if($res===true){
            (new AllocateLogModel())->addLogByAction('removeCustomer',$customerInfo,'扔回成功');
            $this->setjsonReturn('扔回成功');
        }else{
            (new AllocateLogModel())->addLogByAction('removeCustomer',$customerInfo,'扔回失败');
            $this->errorjsonReturn('扔回失败');
        }
    }
    /**
     * 领取客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 customer_id客户的id号
     * @return json数据
     */
    public function receive(){
        $customerInfo = D("CustomerSale")->findCustomerByWhere(array('customer_id'=>I("get.customer_id")));
        $hasPowerLookCustomer=D("CustomerSale")->hasPowerLookCustomer($customerInfo,$this->user_id);
        if($hasPowerLookCustomer!==2&&$hasPowerLookCustomer!==1){$this->errorjsonReturn($hasPowerLookCustomer);}

        //下面判断一天之内领取的客户是否超限
        if((new AllocateLogModel())->receiveCountByWhere($this->user_id)>=C('MaxReceiveCustomerNum')){
            $this->errorjsonReturn('您今天客户领取已到上限100');
        }

        M("CustomerSale")->startTrans();
        $res = D("CustomerSale")->updateCustomerByType(I("get.customer_id"),session("user_info.user_id"),"receive",session("user_info.name"));
        if($res===true){
            if(D('AllocateLog')->addLogByAction('receive',$customerInfo,'领取成功')){
                M("CustomerSale")->commit();
                $this->setjsonReturn('领取成功');
            }else{
                M("CustomerSale")->rollback();
                $this->errorjsonReturn('写入记录失败,请重试');
            }
        }else{
            D('AllocateLog')->addLogByAction('receive',$customerInfo,'领取失败');
            $this->errorjsonReturn('领取失败');
        }
    }
    /**
     * 保留客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 customer_id客户的id号
     * @return json数据
     */
    public function retain(){
        $myCustomerNum = D('CustomerSale')->where(array('owner_user_id'=>$this->user_id,'retain'=>1,'customer_type'=>'意向客户'))->count();
        if($myCustomerNum>=C('maxCustomerNum')){$this->errorjsonReturn('您保留的客户不能大于'.C('maxCustomerNum'));}
        $res = D("CustomerSale")->updateCustomerByType(I("get.customer_id"),session("user_info.user_id"),"retain");
        exit($res == false?$this->errorjsonReturn('保留失败,请重试'):$this->setjsonReturn('保留成功'));
    }
    /**
     * 取消保留客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 customer_id客户的id号
     * @return json数据
     */
    public function unretain(){
        $res = D("CustomerSale")->updateCustomerByType(I("get.customer_id"),session("user_info.user_id"),"unretain");
        exit($res == false?$this->errorjsonReturn('取消失败'):$this->setjsonReturn('取消成功'));
    }
    /**
     * 查看客户的电话号
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @param get方式 传入参数:customer_id  客户的id
     * @return json数据
     */
    public function lookInfo(){
        $customerInfo = D("CustomerSale")->findCustomerByWhere(array('customer_id'=>I("get.customer_id")));
        if($customerInfo){
            $hasPowerLookCustomer=D("CustomerSale")->hasPowerLookCustomer($customerInfo,$this->user_id);
            if($hasPowerLookCustomer===2){$this->setjsonReturn($customerInfo);}
            if($hasPowerLookCustomer!==1){$this->errorjsonReturn($hasPowerLookCustomer);}

            //查看客户
            $customerInfo['phone_view_time']=time();
            $customerInfo['phone_view_uid']=$this->user_id;
            $customerInfo['phone_view_count']=$customerInfo['phone_view_count']+1;
            M("CustomerSale")->startTrans();
            if(D("CustomerSale")->addOrSave($customerInfo,'查看手机号')===true){
                if(D('AllocateLog')->addLogByAction('lookInfo',$customerInfo,'查看成功')){
                    M("CustomerSale")->commit();
                    $this->setjsonReturn($customerInfo);
                }else{
                    M("CustomerSale")->rollback();
                    $this->errorjsonReturn('写入记录失败,请重试');
                }
            }else{
                D('AllocateLog')->addLogByAction('lookInfo',$customerInfo,'查看失败');
                $this->errorjsonReturn('查看客户失败,请重试');
            }
        }else{
            $this->errorjsonReturn('不存在这个客户');
        }
    }
    /**
     * 添加和修改客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @post动作就是操作动作
     * @get进入界面动作
     * @return json数据
     */
    public function add(){
        if(IS_POST){
            $array=I('post.');
            foreach($array as $key=>$value){$array[$key]=trim($value);}
            if($array['customer_name']==''){$this->errorjsonReturn('请填写正确的用户名');}
            if(isset($array['telephonenumber']) && !preg_match("/[0-9]{11}$/", $array['telephonenumber'])){$this->errorjsonReturn('请填写正确手机号码');}
            if(!preg_match("/^[0-9]+(.[0-9]{1,2})?$/", $array['loan_amount'])){$this->errorjsonReturn('请填写正确的意向金额');}

            if(!$array['customer_id']){//在创建的情况下
                //先判断有没有这个手机号码,有的话不让他创建
                if(D('CustomerSale')->findCustomerByWhere(array('telephonenumber'=>phone_encode($array['telephonenumber'])))){$this->errorjsonReturn('手机号码已经存在');}
                $array['city']=D('Contract')->getCityByUserid($this->user_id);//找出创建人的所在的城市
                $array['owner_user_name']=$this->user_info['name'];//拥有人名字
                $array['owner_user_id']=$this->user_id;//拥有人
                $array['creater_user_id']=$this->user_id;//创建人
                $array['create_time']=time();//创建时间
                $array['receive_time']=time();//领取时间
            }else{
                unset($array['telephonenumber']);
                /*if(isset($array['telephonenumber'])){
                    $where1=array();
                    $where1['customer_id']=array('neq',$array['customer_id']);
                    $where1['telephonenumber']=array('eq',phone_encode($array['telephonenumber']));

                    $cus=D('CustomerSale')->findCustomerByWhere($where1);
                    if($cus){$this->errorjsonReturn('请更换号码,这个号码已经被别人使用');}
                }*/
            }

            $address=I('post.address');
            $array['customer_address']=$address['province']."\n".$address['city']."\n".$address['area']."\n".$address['street']."\n";//客户的地址
            $array['house_location']=$address['provinces']."\n".$address['citys']."\n".$address['areas']."\n".$address['streets']."\n";//房产所在区域
            //在修改的情况下,就不用想ocdc发送信息
            if($array['customer_id']){
                D('CustomerSale')->addOrSave($array)?$this->setjsonReturn('操作成功'):$this->errorjsonReturn('操作失败');
            }

            $insert = $array;
            $explode = explode("\n",$insert['house_location']);
            if(in_array($explode[0],$this->limitcity)){$insert['city'] = $explode[0];}
            if(in_array($explode[1],$this->limitcity)){$insert['city'] = $explode[1];}
            if($insert['house_amount']){$insert['house_status'] = '有';}
            if($insert['house_amount'] == '无房'){$insert['house_status'] = '无';}
            $insert['name'] = $insert['customer_name'];
            $re = post_url(C("OCDC_URl"),$insert);
            $json_decode = json_decode($re,true);
            if($json_decode['errNum']===0){
                D('CustomerSale')->addOrSave($array)?$this->setjsonReturn('操作成功'):$this->errorjsonReturn('操作失败');
            }else{
                $this->errorjsonReturn($json_decode['errMsg']);
            }
        }else{
//            $customer_source=(new UserModel())->getCustomerSource();
//            $this->assign('origin',$customer_source['customer_source']);//客户来源
//            $this->assign('utm_source',$customer_source['utm_source']);//渠道
            if(I('get.id')){
                $m = D("CustomerSale");
                $data = $m->findCustomerByWhere(array('customer_id' => I('get.id')));

                if($_SESSION['user_info']['look_phone'] == 2){
                    $data['telephonenumber'] = $data['telephonenumber'];
                }elseif($_SESSION['user_info']['look_phone'] == 1){
                    $data['telephonenumber']=$data['owner_user_id']==$_SESSION['user_info']['user_id']?$data['telephonenumber']:substr($data['telephonenumber'], 0 , 7)."****";
                }else{
                    $data['telephonenumber'] = substr($data['telephonenumber'], 0 , 7)."****";
                }

                $data['customer_address'] = explode("\n",$data['customer_address']);
                $data['house_location'] = explode("\n",$data['house_location']);
                $this->assign('data',$data);
            }
            $this->assign('customer_classify_SET',C('customer_classify'));
            $this->display();
        }
    }




    /**
     * 查看客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @post动作就是操作动作
     * @get进入界面动作
     * @return json数据
     */
    public function view(){
        $this->assign('customer_id',I('get.id'));
        $this->display();
    }


    /**
     * 客户基本信息tab窗口
     * @param $customer_id
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function tabBaseInfo($customer_id){
        $data = D("CustomerSale")->findCustomerByWhere(array('customer_id' => $customer_id));
        $data['customer_address'] = explode("\n",$data['customer_address']);
        $data['house_location'] = explode("\n",$data['house_location']);
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 客户基本房产信息tab窗口
     * @param $customer_id
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function tabEstateInfo($customer_id){
        $data = D("CustomerSale")->findCustomerByWhere(array('customer_id' => $customer_id));
        $data['customer_address'] = explode("\n",$data['customer_address']);
        $data['house_location'] = explode("\n",$data['house_location']);
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 客户附件列表tab窗口
     * @param $customer_id
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-08-08
     */
    public function tabDocs($customer_id){
        $this->assign('upfileinfos', D("CustomerSale")->customerDocument($customer_id));
        $this->display();
    }

    /**
     * 查看客户
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-08-08
     * @post动作就是操作动作
     * @get进入界面动作
     * @return json数据
     */
    public function getCustomer(){
        $data = D("CustomerSale")->findCustomerByWhere(array('customer_id' => I('get.id')));
        if($data){
            $this->setjsonReturn($data);
        }else{
            $this->errorjsonReturn('不存在这个客户');
        }
    }

    /**
     * 手动分配
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param  get customer_id   客户id
     * @return  json数据
     */
    public function allocate(){
        if(IS_POST){
            if(intval(I('post.customer_id')) == 0 || intval(I('post.owner_user_id'))==0 ){
                $this->errorjsonReturn('分配失败');
            }
            M('allocateLog')->startTrans();
            D('CustomerSale')->allocateLog(I('post.customer_id'),I('post.owner_user_id'),2);
            $post = I('post.');
            $post['receive_time'] = time();
            if(D('CustomerSale')->addOrSave($post,'手动分配')){
                M('allocateLog')->commit();
                //发送短信提醒
                $MSG = new Message();
                $User = new User();
                $user_info = $User->getUserInfoByID(I('post.owner_user_id'));
                $content = $_SESSION['user_info']['name'].'分配给了你1个客户,请注意跟进。';
                if($user_info['telephone'] && $content){
                    $phone['user_telephone'] = $user_info['telephone'];//业务员手机号
                    $phone['content'] = $content;//短信内容
                    $phone['create_time'] = time();
                    M("phone_log")->add($phone);
                }
                $MSG->send($user_info['telephone'],$content);

                $this->setjsonReturn('分配成功');
            }else{
                M('allocateLog')->rollback();
                $this->errorjsonReturn('分配失败');
            }
        }else{
            //if(in_array(C('ROLE.TDZ'),$_SESSION['user_info']['role_names']) || in_array(C('ROLE.FGSJL'),$_SESSION['user_info']['role_names']) || in_array(C('ROLE.CRMZL'),$_SESSION['user_info']['role_names']) || in_array(C('ROLE.XSBJL'),$_SESSION['user_info']['role_names'])){
                $this->assign('customer_id',intval(I('get.id')));
                $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"),'all');
                if(!empty($users_arr)){
                    $get = I('get.');
                    $get['user_ids'] = $users_arr;
                    $return = D('User')->getUserTable($get);
                    $this->assign('user_list', $return[0]);
                    $count = $return[2];
                    $total = $count%5 ? intval($count/5)+1 : intval($count/5);
                    $this->assign('count',$count);
                    $this->assign('total',$total);
                    $this->assign('pageBar', $return[1]);
                }
                $department_list = json_decode(post_url(C('BASE_getSubDepartmentList'),array('department_id'=>$_SESSION['user_info']['department_id'],'data_type'=>$_SESSION['user_info']['data_type'])),true);
                $this->assign('department_list',$department_list);
                $this->display();
//            }else{
//                echo '您没有分配权限';
//            }
        }

    }
    /**
     * 批量分配
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param  get customer_id   客户id数组
     * @param  get city   所在城市
     * @return  bool
     */
    public function allocateAll(){
        if(IS_POST){
            $result = D("CustomerSale")->allocateAll(I('post.'));
            $this->ajaxReturn($result);
        }else{
            //if(in_array(C('ROLE.TDZ'),$_SESSION['user_info']['role_names']) || in_array(C('ROLE.FGSJL'),$_SESSION['user_info']['role_names']) || in_array(C('ROLE.CRMZL'),$_SESSION['user_info']['role_names']) || in_array(C('ROLE.XSBJL'),$_SESSION['user_info']['role_names'])){
                $users_arr=D('Contract')->getSubUserByUserId(session("user_info.user_id"),'all');
                if(!empty($users_arr)){
                    $get = I('get.');
                    $get['user_ids'] = $users_arr;
                    $return = D('User')->getUserTable($get);
                    $this->assign('user_list', $return[0]);
                    $count = $return[2];
                    $total = $count%5 ? intval($count/5)+1 : intval($count/5);
                    $this->assign('count',$count);
                    $this->assign('total',$total);
                    $this->assign('pageBar', $return[1]);
                }
                $department_list = json_decode(post_url(C('BASE_getSubDepartmentList'),array('department_id'=>$_SESSION['user_info']['department_id'],'data_type'=>$_SESSION['user_info']['data_type'])),true);
                $this->assign('department_list',$department_list);
                $customer_id = array_filter(explode(',',I('customer_ids')));
                $this->assign('customer_num',count($customer_id));
                $this->display();
//            }else{
//                echo '您没有分配权限';
//            }
        }
    }

    /**
     * 显示沟通日志列表
     *
     */
    public function showCommunicationLog(){
        $list = D('CommunicationLog')->communicationLogList(I('get.id'));
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 沟通日志
     * @author <zengzhiqiang@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @post 添加沟通日志动作
     * @get 添加沟通日志界面
     * @return json数据
     */
    public function communicationLog(){
        if(IS_POST){
            //获取客户的基本信息
            $customer_id=I('post.communcationlog')['customer_id'];
            $customerInfo=D('CustomerSale')->findCustomerByWhere(array('customer_id'=>$customer_id));
            $data = I('post.communcationlog');

            //更新客户的跟踪时间
            $array['communitime']=time();
            $array['customer_id']=$customer_id;
            //判断客户是不是首次沟通
            if($customerInfo['first_communitime_time']==0){$array['first_communitime_time']=time();}

            //如果有传入有无房产过来
            if($data['house_status']){
                //如果客户的负责人是自己,并且没有被确认,就让业务员为客户选择房产客户选择房产
                if($customerInfo['owner_user_id']==session("user_info.user_id")&&!D('CommunicationLog')->hasConfirmed($customer_id)){
                    $array['house_status']=$data['house_status'];
                    $array['loan_amount']=$data['loan_amount'];
                }
            }

            if(!D('CustomerSale')->addOrSave($array))
                $this->errorjsonReturn('写入房产信息失败');

            $data['next_contact_time'] = strtotime($data['next_contact_time']);
            $data['create_user_id'] = session("user_info.user_id");
            $data['create_time'] = time();

            if(D('CommunicationLog')->create($data)){
                D('CommunicationLog')->addOrSave($data)?$this->setjsonReturn('添加成功'):$this->errorjsonReturn('添加失败');
            }else{
                $this->errorjsonReturn('添加失败');
            }
        }else{
            $customerInfo=D('CustomerSale')->findCustomerByWhere(array('customer_id'=>I('get.id')));
            //如果客户的负责人是自己,并且没有被确认,就显示让客户选择房产
            $wantUpCustomer=0;
            if(!D('CommunicationLog')->hasConfirmed(I('get.id'))){
                $wantUpCustomer=1;
            }else{
                $this->assign('useful',D('CommunicationLog')->getUseful(I('get.id')));
            }
            $this->assign('wantUpCustomer',$wantUpCustomer);
            if($customerInfo){
                $this->assign('customer_info',$customerInfo);
            }
            $this->assign('id',I('get.id'));
            $list = D('CommunicationLog')->communicationLogList(I('get.id'));
            $this->assign('list',$list);
            $this->display();
        }
    }

    /**
     * 客户的被领取分配的记录
     * @author <dingwenzai@fangjinsuo.com>
     * @createdate: 2016-11-02
     */
    public function customerWay(){
        $customerWay=D('AllocateLog')->selectCustomerWayLog(I('get.customer_id'));
        $this->assign('customerWay',$customerWay);
        $this->display();
    }
}