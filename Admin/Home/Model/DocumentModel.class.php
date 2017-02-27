<?php
namespace Home\Model;
use Think\Model;
class DocumentModel extends Model
{
    /**
     * 上传附件
     * @author ckl
     * @createdate: 2016-07-14
     * @return array
     */
    public function uploadDocument(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->allowExts  = array('pdf','doc','docx','xls','xlsx','txt','rar','zip','jpg','png','jpeg','gif');// 设置附件上传类型
        $upload->autoSub = true;
        $upload->subType = 'date';
        $upload->dateFormat = 'Y-m-d';
        $upload->maxSize  = 20*1024*1024 ;// 设置附件上传大小 20M
        $upload->thumb = true;
        $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '500,100';
        //设置缩略图最大高度
        $upload->thumbMaxHeight = '500,100';
        $info   = $upload->upload();
        if($info){  // 上传成功
            return array('status'=>1,'data'=>$info);
        }else{      // 上传错误提示错误信息
            return array('status'=>-1,'data'=>$upload->getError());
        }
    }
    /**
     * 新增上传附件记录
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $document_data
     * @return array|int
     */
    public function addDocument($document_data)
    {
        if(!$document_data){ return -1; }
        //$file = explode('/',$document_data['savename']);
        $data['document_name'] = $document_data['name'];
        $data['document_type'] = $document_data['type'];
        $data['document_size'] = $document_data['size'];
        $data['document_savename'] = $document_data['savename'];
        $data['document_ext'] = $document_data['ext'];
        $data['document_savepath'] = $document_data['savepath'];
        $data['document_md5'] = $document_data['md5'];

        $document = M('document');

        $id = $document->add($data);

        if($id){
            return array('document_id'=>$id,'document_savename'=>$data['document_savename'],'document_savepath'=>$data['document_savepath'],'document_ext'=>$data['document_ext']);
        }
        else{
            return -2;
        }
    }

    /**
     * 新增上传附件与人关联记录
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $contract_data
     * @param $customer_id
     * @param $document_id
     * @param $contract_id
     * @return int|mixed
     */
    public function addcontractDocument($contract_data,$document_id, $customer_id, $contract_id,$creator_id)
    {
        if(!($contract_data) || !($document_id) || !($contract_id || $customer_id) ){
            return -1;
        }
        $data = array();

        $data['customer_id'] 		= $customer_id;
        $data['contract_id'] 		= $contract_id;
        $data['document_id'] 		= $document_id;
        $data['creator_id'] 		= $creator_id;
        $data['document_c_id'] 		= $contract_data['document_c_id'];
        $data['rc_document_source']	= $contract_data['rc_document_source'];
        $data['rc_document_create_time'] 		= time();
        $data['rc_document_update_time'] 		= time();
        $data['rc_document_status'] 			= 1;
        $contract_document = M('r_contract_document');
        $id = $contract_document->add($data);
        if($id){
            return $id;
        }
        else{
            return -2;
        }
    }

    /**
     * 新建文档
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @document 文档内容
     * @customer_id 客户ID
     * @contract_id 合同ID
     * @category_id 类别ID
     * @create_id业务员ID
     *
     * @return array
     *
     */
    public function newDocument($document,$customer_id, $contract_id,$category_id,$creator_id)
    {
        $status = 1;
        foreach($document as $k=> $data){

            //检查文件是否重复
            $document_id = $this->checkDocumentUniq($data['md5']);


            if($document_id) {
                $server_documents = $this->getDocumentInfo($document_id);
            }else{
                $server_documents = $this->addDocument($data);
                $document_id = $server_documents['document_id'];
            }
            if($document_id<0){
                $status = -1;
            }

            $contract_document_data = array();
            $contract_document_data['document_c_id']      = $category_id;
            if($this->isMobile()){
                $contract_document_data['rc_document_source'] = 'MOBILE';
            }else{
                $contract_document_data['rc_document_source'] = 'PC';
            }

            $rc_document_id = $this->addcontractDocument($contract_document_data,$document_id,$customer_id, $contract_id,$creator_id);
            if($rc_document_id<0){
                $status = -1;
            }
            $contract_info = $this->getcontractInfo($contract_id);
            $up_data[$k]['document'] = trim(UPLOAD_PATH,'.').$server_documents['document_savepath'].'/'.$server_documents['document_savename'];
            if(in_array($server_documents['document_ext'] ,array('jpg','png','jpeg','gif'))){
                $up_data[$k]['m_document'] = trim(UPLOAD_PATH,'.').$server_documents['document_savepath'].'/m_'.$server_documents['document_savename'];
                $up_data[$k]['s_document'] = trim(UPLOAD_PATH,'.').$server_documents['document_savepath'].'/s_'.$server_documents['document_savename'];
            }

            $category_info = $this->getCategoryInfo($category_id);
            $up_data[$k]['contract_document_id'] = $rc_document_id;
            $up_data[$k]['category_id'] = $category_id;
            $up_data[$k]['category_name'] =  $category_info['document_c_name_header'].' '.$category_info['document_c_name'];
            $up_data[$k]['contract_id'] = $contract_id;
            $up_data[$k]['ext'] = $server_documents['document_ext'];
            $up_data[$k]['name'] = $data['name'];
            $up_data[$k]['m_name'] = $contract_info['name']."-". $category_info['document_c_name_header'].' '.$category_info['document_c_name'].'-'.$rc_document_id.'.'.$server_documents['document_ext'];
            $up_data[$k]['up_name'] = session('name');
            $up_data[$k]['up_time'] = date('Y-m-d');
        }
        return array('status'=>$status,'return'=>$up_data );
    }
    /**
    * 通过MD5检查附件是否重复
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
    * @param $md5
    * @return bool|mixed
    */
    public function checkDocumentUniq($md5)
    {
        if(!$md5){ return FALSE; }
        $id = $this->where('document_md5='."'{$md5}'")->getField('document_id');
        if($id){
            return $id;
        }else{
            return FALSE;
        }
    }
    /**
     * 获取合同文档
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $contract_id
     * @return bool|mixed
     */
    public function getDocumentBycontractId($contract_id)
    {
        if(!$contract_id){
            return FALSE;
        }

        $contract = M('contract');
        $contract_info = $contract->where('contract_id='.$contract_id)->find();
        $DB_PREFIX = C("DB_PREFIX");
        $sql = "SELECT cd.*,d.document_savename,d.document_savepath,d.document_ext,d.document_md5,dc.document_c_name,dc.document_c_name_header
            from ".$DB_PREFIX."r_contract_document cd LEFT JOIN  ".$DB_PREFIX."document d on cd.document_id = d.document_id  LEFT JOIN  ".$DB_PREFIX."document_category dc on cd.document_c_id = dc.document_c_id WHERE  cd.contract_id='{$contract_id}' AND cd.rc_document_status=1 AND  cd.document_c_id !=0 ORDER  BY cd.rc_document_id desc";
        $contract_document = M()->query($sql);

        //echo $sql;

        foreach($contract_document as $k => $v){
            $top_category = $this->getTopCategory($v['document_c_id']);
            $name = $contract_info['name']."-". $v['document_c_name_header'].' '.$v['document_c_name'].'-'.$v['rc_document_id'].'.'.$v['document_ext'];
            $path = trim(UPLOAD_PATH,'.').$v['document_savepath'].'/'.$v['document_savename'];
            $data = array('document_name'=>$name,'document_path'=>$path,'id'=>$v['document_id'],'ext'=> $v['document_ext'],'categor_idy'=>$v['document_c_id'],'category'=>$v['document_c_name_header'].' '.$v['document_c_name']);
            if(in_array($v['document_ext'] ,array('jpg','png','jpeg','gif')))
            {
                $data['m_document_path'] = C('IMAGE_PATH').'/Uploads/'.$v['document_savepath'].'m_'.$v['document_savename'];
                $data['s_document_path'] = C('IMAGE_PATH').'/Uploads/'.$v['document_savepath'].'s_'.$v['document_savename'];
            }
            //$data['s_document_path'] = htmlspecialchars(C('UPLOAD_SERVICE_URL')."file/thumb_pic?resourceName=".$v['document_md5']."&w=200");
            //$data['m_document_path'] = htmlspecialchars(C('UPLOAD_SERVICE_URL')."file/thumb_pic?resourceName=".$v['document_md5']."&w=600");
            $document[$top_category][$k] = $data;
        }
        $resault['contract_id'] = $contract_info['contract_id'];
        $resault['contract_name'] = $contract_info['borrower'];
        $resault['document'] = $document;
        return $resault;

    }


    /**
     * 获取客户文档
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $customer_id
     * @return bool|mixed
     */
    public function getDocumentByCustomerId($customer_id){
        if(!$customer_id){
            return FALSE;
        }

        $customer = M('CustomerSale');
        $customer_info = $customer->where('customer_id='.$customer_id)->find();
        $DB_PREFIX = C("DB_PREFIX");
        $sql = "SELECT cd.*,d.document_savename,d.document_savepath,d.document_ext,dc.document_c_name,dc.document_c_name_header from
      ".$DB_PREFIX."r_contract_document cd LEFT JOIN  ".$DB_PREFIX."document d on cd.document_id = d.document_id
      LEFT JOIN  ".$DB_PREFIX."document_category dc on cd.document_c_id = dc.document_c_id WHERE  cd.customer_id='{$customer_id}' AND cd.rc_document_status=1 AND  cd.document_c_id !=0 ORDER  BY cd.rc_document_id desc";

        $customer_document = M()->query($sql);

        foreach($customer_document as $k => $v){
            $top_category = $this->getTopCategory($v['document_c_id']);
            $name = $customer_info['name']."-". $v['document_c_name_header'].' '.$v['document_c_name'].'-'.$v['rc_document_id'].'.'.$v['document_ext'];
            $path = C('IMAGE_PATH').'/Uploads/'.$v['document_savepath'].$v['document_savename'];
            $data = array('document_name'=>$name,'document_path'=>$path,'id'=>$v['document_id'],'ext'=> $v['document_ext'],'categor_idy'=>$v['document_c_id'],'category'=>$v['document_c_name_header'].' '.$v['document_c_name']);
            if(in_array($v['document_ext'] ,array('jpg','png','jpeg','gif')))
            {

                $data['m_document_path'] =  C('IMAGE_PATH').'/Uploads/'.str_replace('/','',$v['document_savepath']).'/'.'m_'.$v['document_savename'];
                $data['s_document_path'] =  C('IMAGE_PATH').'/Uploads/'.str_replace('/','',$v['document_savepath']).'/'.'s_'.$v['document_savename'];
            }
            $document[$top_category][$k] = $data;
        }
        $resault['customer_id'] = $customer_info['customer_id'];
        $resault['customer_name'] = $customer_info['customer_name'];
        $resault['document'] = $document;
        return $resault;
    }

    /**
     * 新增上传附件与人关联记录
     * @param $customer_data
     * @param $document_id
     * @param $customer_id
     * @return int|mixed
     */
    public function addCustomerDocument($customer_data,$document_id,$customer_id,$creator_id)
    {
        if(!($customer_data) || !($document_id) || !($customer_id || !($creator_id)) ){
            return -1;
        }
        $data = array();

        $category_info = $this->getCategoryInfo($customer_data['document_c_id']);

        $category_name = $category_info['document_c_name'];
        $count = M('r_contract_document')->where('document_c_id=%d and customer_id = %d',$customer_data['document_c_id'],$customer_id)->count('rc_document_id');
        $count ++;
        $data['customer_id'] 		= $customer_id;
        $data['document_id'] 		= $document_id;
        $data['creator_id'] 		= $creator_id;
        $data['document_c_id'] 		= $customer_data['document_c_id'];
        $data['document_name'] 		= $category_name.'-'.$count;
        if($customer_data['rc_document_source'] == 'MOBILE'){
            $data['document_name']  .= '-MOBILE';
        }
        $data['document_name'] .= '.'.$customer_data['ext'];
        $data['rc_document_source']	= $customer_data['rc_document_source'];
        $data['rc_document_create_time'] 		= time();
        $data['rc_document_upeate_time'] 		= time();
        $data['rc_document_status'] 			= 1;
        $customer_document = M('r_contract_document');
        $id = $customer_document->add($data);

        if($id){
            return array('rc_document_id'=>$id,'document_name'=>$data['document_name']);
        }
        else{
            return -2;
        }
    }

    /**
     * 新建客户文档
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-10-11
     * @document 文档内容
     * @customer_id 客户ID
     * @category_id 类别ID
     * @creator_id业务员ID
     * @document_source 文件来源
     *
     * @return array
     *
     */
    public function newCustomerDocument($document,$customer_id,$category_id,$creator_id,$document_source){
        $status = 1;
        foreach($document as $k=> $data){

            //检查文件是否重复
            $document_id = $this->checkDocumentUniq($data['md5']);


            if($document_id) {
                $server_documents = $this->getDocumentInfo($document_id);
            }else{
                $server_documents = $this->addDocument($data);
                $document_id = $server_documents['document_id'];
            }
            if($document_id<0){
                $status = -1;
            }

            $contract_document_data = array();
            $contract_document_data['document_c_id']      = $category_id;
            $contract_document_data['ext']      		  =  $data['ext'];
            $contract_document_data['rc_document_source'] = $document_source;


            $rc_documents = $this->addCustomerDocument($contract_document_data,$document_id,$customer_id,$creator_id);

            if($rc_documents['rc_document_id']<0){
                $status = -1;
            }
            $customer_info = $this->getCustomerInfo($customer_id);
            $up_data[$k]['document'] = trim(UPLOAD_PATH,'.').$server_documents['document_savepath'].'/'.$server_documents['document_savename'];
            if(in_array($server_documents['document_ext'] ,array('jpg','png','jpeg','gif'))){
                $up_data[$k]['m_document'] = trim(UPLOAD_PATH,'.').$server_documents['document_savepath'].'/m_'.$server_documents['document_savename'];
                $up_data[$k]['s_document'] = trim(UPLOAD_PATH,'.').$server_documents['document_savepath'].'/s_'.$server_documents['document_savename'];
            }

            $category_info = $this->getCategoryInfo($category_id);
            $up_data[$k]['customer_document_id'] = $rc_documents['rc_document_id'];
            $up_data[$k]['category_id'] = $category_id;
            $up_data[$k]['category_name'] =  $category_info['document_c_name_header'].' '.$category_info['document_c_name'];
            $up_data[$k]['customer_id'] = $customer_id;
            $up_data[$k]['ext'] = $server_documents['document_ext'];
            $up_data[$k]['name'] = $rc_documents['document_name'];
            $up_data[$k]['m_name'] = $customer_info['name']."-". $category_info['document_c_name_header'].' '.$category_info['document_c_name'].'-'.$rc_documents['rc_document_id'].'.'.$server_documents['document_ext'];
            $up_data[$k]['up_name'] = session('name');
            $up_data[$k]['up_time'] = date('Y-m-d');
        }
        return array('status'=>$status,'return'=>$up_data );
    }

    /**
     * 获取用户信息
     * @param $id
     * @return bool|mixed
     */
    public function getCustomerInfo($id)
    {
        if(!$id){
            return FALSE;
        }
        $customer = M('CustomerSale');

        $resault = $customer->where('customer_id='.$id)->find();

        if($resault){
            return $resault;
        }
        else{
            return FALSE;
        }
    }


    /**
     * 获取最上级分类名称
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $document_c_id
     * @return int
     */
    public function getTopCategory($document_c_id)
    {
        if(!$document_c_id)
        {
            return 0;
        }
        $category = $this->getCategoryInfo($document_c_id);
        if(!$category){
            return 0;
        }
        if($category['document_c_parent_id'] == 0){
            return $category['document_c_id'];
        }else{
            return $this->getTopCategory($category['document_c_parent_id']);
        }
    }
    /**
     * 检查客服是否拥有该手机用户
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $phone_number
     * @param $role_id
     * @return bool|mixed
     */
    public function checkMobileDocument($phone_number,$role_id)
    {
        if(!($phone_number) || !($role_id)){
            return FALSE;
        }
        $customer_id = M("CustomerSale")->where(array("telephonenumber" =>$phone_number , 'owner_user_id'=> $role_id))->getField("customer_id");
        if($customer_id){
            return $customer_id;
        }else{
            return FALSE;
        }

    }
    /**
     * 获取附件信息
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @return bool|mixed
     */
    public function getDocumentInfo($id)
    {
        if(!$id){
            return FALSE;
        }

        $document = M('document');

        $resault = $document->where('document_id='.$id)->find();

        if($resault){
            return $resault;
        }
        else{
            return FALSE;
        }
    }

    /**
     * 删除文档
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @return bool|mixed
     */
    public function deleteDoc($id,$role_id,$admin='')
    {
        if(!$id){
            return FALSE;
        }
        //检查权限
        $data = $this->checkcontract($role_id,$admin);

        if(!$data){
            return -2;
        }
        $resault = M('r_contract_document')->where('rc_document_id = %d',$id)->setField('rc_document_status',0);
        if($resault){
            return 1;
        }
        else{
            return -1;
        }
    }

    /**
     * 检查客服是否拥有该用户
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $role_id
     * @param $admin
     * @return bool|mixed
     */
    public function checkcontract($role_id,$admin='')
    {

        if(!$role_id){
            return FALSE;
        }

        if($admin){
            return TRUE;
        }
        $contract_id = M('contract')->where("create_user_id='{$role_id}' ")->getField('contract_id');
        if($contract_id){

            return $contract_id;
        }else{
            return FALSE;
        }
    }

    /**
     * 获取客服附件关联信息
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @return bool|mixed
     */
    public function getcontractDocumentInfo($rc_document_id)
    {
        if(!$rc_document_id){
            return FALSE;
        }
        $DB_PREFIX = C("DB_PREFIX");
        $sql = "SELECT cd.*,dc.document_c_name,dc.document_c_name_header from ".$DB_PREFIX."r_contract_document cd  LEFT JOIN
        ".$DB_PREFIX."document_category dc on cd.document_c_id = dc.document_c_id WHERE  cd.rc_document_id='{$rc_document_id}'";
        $resault = M()->query($sql);
        if($resault[0]){
            return $resault[0];
        }
        else{
            return FALSE;
        }
    }

    /**
     * 获取用户信息
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @return bool|mixed
     */
    public function getcontractInfo($id)
    {
        if(!$id){
            return FALSE;
        }
        $contract = M('contract');

        $resault = $contract->where('contract_id='.$id)->find();

        if($resault){
            return $resault;
        }
        else{
            return FALSE;
        }
    }

    /**
     * 获取下载附件信息
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @return array|bool
     */
    public function getDownloadInfo($id)
    {
        $dc_info = $this->getcontractDocumentInfo($id);

        if(!$dc_info){
            return false;
        }
        $d_info = $this->getDocumentInfo($dc_info['document_id']);
        if(!$d_info){
            return false;
        }
        $contract = $this->getcontractInfo($dc_info['contract_id']);
        if(!$contract){
            return false;
        }
        $file_name = $contract['name']."-".$dc_info['document_c_name'].'-'.$dc_info['rc_document_id'];
        if($dc_info['rc_document_source'] == 'MOBILE')
        {
            $file_name .= '-mobile';
        }elseif($dc_info['rc_document_source'] == 'PC'){
            $file_name .= '-pc';
        }
        $file_name .= '.'.$d_info['document_ext'];
        $path = UPLOAD_PATH.$d_info['document_savepath'].'/'.$d_info['document_savename'];
        $return = array(
            'name' 	=> $file_name,
            'path'	=> $path,
            'type'	=> $d_info['document_type'],

        );
        return $return;
    }

    /**
     * 转换类别
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @param $category
     * @return array|bool
     */
    public function changeCategory($id,$category)
    {
        if(!(is_numeric( $id)) || !($category)){
            return array('status'=>0,'message'=>'传入参数错误','data'=>'');
        }
        $document = M('r_contract_document');
        $data = array('document_c_id'=>$category);
        $resault = $document->where('rc_document_id='.$id)->save($data);

        if($resault){
            return array('status'=>1,'message'=>'成功','data'=>'');
        }
        else{
            return array('status'=>0,'message'=>'失败','data'=>'');
        }
    }

    /**
     * 转换类别
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $id
     * @param $category
     * @return array|bool
     */
    public function changeCategoryForMobile($id,$category)
    {
        if(!(is_numeric( $id)) || !($category)){
            return array('status'=>0,'message'=>'传入参数错误','data'=>'');
        }
        $document_c_id = M('document_category')->where("document_c_name_header='{$category}'")->getField('document_c_id');
        $document = M('r_contract_document');
        $data = array('document_c_id'=>$document_c_id);
        $resault = $document->where('rc_document_id='.$id)->save($data);

        if(FALSE !== $resault){
            return array('status'=>1,'message'=>'成功','data'=>'');
        }
        else{
            return array('status'=>0,'message'=>'失败','data'=>'');
        }
    }

    /**
     * 获取下级分类
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $document_c_id
     * @return array|bool
     */
    public function getNextCategory($document_c_id = 0)
    {
        if(!is_numeric($document_c_id)){
            return FALSE;
        }
        $document_category = M('document_category');
        $category = $document_category->where("document_c_parent_id='{$document_c_id}' and document_c_status=1")->select();
        if(count($category) > 0){
            foreach($category as $k => $v){
                $category[$k]['name'] = $v['document_c_name_header'].' '.$v['document_c_name'];
            }
            return $category;
        }else{
            return FALSE;
        }
    }

    /**
     * 获取所有分类
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return array|bool
     */
    public function getCategoryForMobile()
    {
        $category = $this->getNextCategory();
        $resault = array();
        foreach($category as $k=>$v){
            $category_2 = $this->getNextCategory($v['document_c_id']);

            if($category_2){
                foreach($category_2 as $k2 => $v2){

                    $category_3 = $this->getNextCategory($v2['document_c_id']);
                    if($category_3){
                        $category_3 = $this->getNextCategory($v2['document_c_id']);
                        foreach($category_3 as $k3 => $v3){
                            $resault[$v['document_c_id']][$v2['document_c_name_header']." ". $v2['document_c_name']][] = $v3['document_c_name_header']." ". $v3['document_c_name'];
                        }
                    }else{
                        $resault[$v['document_c_id']][$v2['document_c_name_header']." ". $v2['document_c_name']][] = $v2['document_c_name_header']." ". $v2['document_c_name'];
                    }
                }
            }else{
                $resault[$v['document_c_id']] = array();
            }
        }
        return json_encode($resault);
    }

    /**
     * 获取父级分类的标头
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $document_c_parent_id
     * @param $document_c_level
     * @return bool
     */
    public function getCategoryParentLevelHeader($document_c_parent_id,$document_c_level)
    {
        if(!($document_c_parent_id) || $document_c_level < 2){
            return FALSE;
        }

        $category = $this->getCategoryInfo($document_c_parent_id);
        $name_header = $category['document_c_level_id']."-";

        if($document_c_level == 2){
            return $name_header;
        }else{
            return $this->getCategoryParentLevelHeader($category['document_c_parent_id'],$category['document_c_level']).$name_header;
        }
    }

    /**
     * 获取分类信息
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @param $document_c_id
     * @return bool
     */
    public function getCategoryInfo($document_c_id)
    {
        if(!$document_c_id){
            return FALSE;
        }
        return M('document_category')->where("document_c_id='{$document_c_id}'")->find();

    }

    /**
     * 判断是否移动端访问
     * @author <ckl@fangjinsuo.com>
     * @createdate: 2016-07-14
     * @return bool
     */
    private function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }
}