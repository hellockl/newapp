<?php

namespace Home\Model;
use Think\Model\ViewModel;

class DocumentContractViewModel extends ViewModel
{
    public $viewFields = array(
        'r_contract_document'=>array('rc_document_id','contract_id','customer_id','document_id','creator_id','document_c_id','document_name','rc_document_create_time','rc_document_update_time','rc_document_source','rc_document_status','_type'=>'LEFT'),
        'document'=>array('document_id','document_name','document_type','document_size','document_ext','document_md5','document_savename','document_savepath','_on'=>'document.document_id=r_contract_document.document_id'),
        'document_category'=>array('document_c_id','document_c_parent_id','document_c_level','document_c_level_id','document_c_name_header','document_c_name','document_c_status','document_c_create_time','document_c_update_time','_on'=>'document_category.document_c_id=r_contract_document.document_c_id')
    );
}