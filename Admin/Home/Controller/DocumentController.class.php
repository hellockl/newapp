<?php
namespace  Home\Controller;
use Home\Controller\BaseController;
use Org\Util\Curl;
/**
 * 业绩管理 模块
 * @author mvp吴
 * @createdate: 2016-08-12
 */
class DocumentController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 添加附件
     * @author mvp吴
     * @createdate: 2016-07-14
     * @param post方式提交数据
     * @param get方式生成界面
     * @return json数据
     */
    public function add(){
        $contract_id=I('request.contract_id', 0, 'intval');
        $customer_id=I('request.customer_id', 0, 'intval');

        //$HasPowerToDo=D('Contract')->HasPowerToDo($contract_id);
        //if($HasPowerToDo!==true){I('post.contract_id')?$this->errorjsonReturn($HasPowerToDo):exit($HasPowerToDo);}

        if(IS_POST) {
            //接收参数
            $category = $_POST['category'];    //分类
            if (!$category) {$category = 0;}
            //先上传图片
            $Document = D('Document');
            $document_data = $Document->uploadDocument();
            if ($document_data['status'] == -1) {
                $resault = array('status' => 0, 'message' => $document_data['data'], 'data' => '');
            } else {
                //如果有合同,通过合同查询客户的id
                if($contract_id){
                    $contractInfo=M('Contract')->where(array('contract_id'=>$contract_id))->find();
                    $agreementInfo=M('Agreement')->where(array('agreement_id'=>$contractInfo['agreement_id']))->find();
                    $customer_id=$agreementInfo['customer_id'];
                }
                //处理上传文件以后的数据
                $data = $Document->newDocument($document_data['data'], $customer_id, $contract_id, $category, session('user_info.user_id'));

                if ($data['status'] == -1) {
                    $resault = array('status' => 0, 'message' => '文件上传失败11', 'data' => '');
                } else {
                    $resault = array('status' => 1, 'message' => '文件上传成功11!', 'data' => $data['return']);
                }
            }
            $this->ajaxReturn($resault);
        }else{
            $this->assign('upload_max_filesize',ini_get('upload_max_filesize'));
            $this->assign('category_level1' ,D('Document')->getNextCategory(0));// 获取第一层分类
            $this->display();
        }
    }



    /**
     * 上传至微服务
     * @author mvp吴
     * @createdate: 2016-07-14
     */
    public function addToService(){

        $ret = Curl::get(C('UPLOAD_SERVICE_URL').'file/getToken?expires=120&key='.C('UPLOAD_KEY'));
        $data = json_decode($ret);
        $token = $data->retData->token;
        $this->assign('token', $token);
        $this->assign('domain', C('UPLOAD_SERVICE_URL'));
        $this->assign('upload_max_filesize',ini_get('upload_max_filesize'));
        $this->assign('category_level1' ,D('Document')->getNextCategory(0));// 获取第一层分类
        $this->display();

    }
    /**
     * 添加附件
     * @author mvp吴
     * @createdate: 2016-07-14
     * @param post方式
     * @return json对象
     */
    public function addDocumentFile(){
        if(IS_POST){
            $contract_id = I('contract_id');
            $category = I('post.category');
            $files = I('post.files');

            $Document = D('Document');
            $data = $Document->newDocument($files, $contract_id, $category,session('user_info.user_id'));

            if ($data['status'] == -1) {
                $resault = array('status' => 0, 'message' => '文件上传失败', 'data' => '');
            } else {
                $resault = array('status' => 1, 'message' => '文件上传成功!', 'data' => $data['return']);
            }
            $this->ajaxReturn($resault);
        }else{
            exit('ERROR');
        }
    }
    /**
     * 获取下一级分类
     * @author mvp吴
     * @createdate: 2016-07-14
     * @param id关联文件的id
     * @return json对象
     */
    public function getNextCategory(){
        $document_c_id = I('post.id') ? I('post.id') : I('get.id');
        $data =  D('Document')->getNextCategory($document_c_id);
        if(!$data){
            $resault = array('status'=>0,'message'=>'无下级分类','data'=>'');
        }else{
            $resault = array('status'=>1,'message'=>'获取成功','data'=>$data);
        }
        $this->ajaxReturn($resault);
    }


    public function tabDocumentInfo(){
        $contractInfo=D('Contract')->contractInfo(I('get.contract_id'));
        $agreementInfo=M('Agreement')->where(array('agreement_id'=>$contractInfo['agreement_id']))->find();
        $documentInfo=D("CustomerSale")->customerDocument($agreementInfo['customer_id']);
        $this->assign('upfileinfos',$documentInfo );

        $this->display();
    }


    /**
     * 下载附件
     */
    public function downloadDocument() {
        //接收参数
        //r_document_customer id
        $id = $_POST['id'];
        import('@.ORG.Net.Http');
        if(!$id)
        {
            $id = $_GET['id'];
        }
        $Document = D('DocumentContractView');
        $info = $Document->where(array('rc_document_id'=>$id))->find();
        $url='Uploads/'.$info['document_savepath'].'/'.$info['document_savename'];

        if(file_exists($url)){
            header("Content-type: octet/stream");
            header("Content-disposition:attachment;filename=".$url.";");
            header("Content-Length:".filesize($url));
            readfile($url);
        }else{
            echo "文件不存在";
        }
    }
    /**
     * 预览图片
     */
    public function previewDocument() {
        //接收参数
        //r_document_customer id
        $id = $_POST['id'];
        import('@.ORG.Net.Http');
        if(!$id)
        {
            $id = $_GET['id'];
        }
        $Document = D('DocumentContractView');
        $info = $Document->where(array('rc_document_id'=>$id))->find();
        $url='Uploads/'.$info['document_savepath'].'/'.$info['document_savename'];

        if(file_exists($url)){
            $size=getimagesize($url);
            $fp=fopen($url,"rb");
            if($size&&$fp){
                header("Content-type:{$size['mime']}");
                fpassthru($fp);
            }
            exit;
        }else{
            echo "<span style='color: #F6F6F6'>文件不存在</span>";
        }
    }
}