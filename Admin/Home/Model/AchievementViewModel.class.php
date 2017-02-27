<?php

namespace Home\Model;
use Think\Model\ViewModel;

class AchievementViewModel extends ViewModel
{
    public $viewFields = array(
        'agreement'=>array('customer_id','number','agreement_id','plan_money','commission'=>'agreement_commission','status','_type'=>'LEFT'),
        'contract'=>array('contract_id','create_user_id','is_delete','status'=>'contract_status','borrower','_on'=>'agreement.agreement_id=contract.agreement_id','_type'=>'LEFT'),
        'receivables'=>array('pay_money','_on'=>'receivables.agreement_id=agreement.agreement_id')
    );
}