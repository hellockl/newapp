<?php
return array(
    'URL_MODEL' => 1,
    'URL_HTML_SUFFIX'       =>  '',
    'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES' => array(
        'm.fang-crm.com' => 'Mobile',
    ),
    'SESSION_OPTIONS' =>array('expire'=>14400),
    'SEC_KEY' => 'Dl34(93^3e$t.oTY',//安全秘钥
    'TELEPHONE_SECRET_KEY' => '123#@!Ff5%5AVSB',//手机加密 密钥

    //审核步骤
    'ALLUSER_ROLE'=>array('','业务员','团队长','分公司经理','财务结案审核'),

    //'配置项'=>'配置值'
    'SELF_RECEIVABLES_NUM' => 2,    //自营合同回款批数
    'CONTRACT_STATUS'   => array('进行中','结案中','已结束'),  //合同审核流程
    'CHECK_PROCESS'     => array( '', '结案申请', '团队长确认', '分公司经理确认', '财务确认'),  //合同审核流程
    'CHECK_PROCESS_STATUS' => array( '等待结案申请', '等待团队长确认', '等待分公司经理确认', '等待财务确认','财务已确认'), //合同审核状态
    //客户等级
    'CUSTOMER_LEVEL'=>array('待联系','空号','未接','无需求','正常','外地','同业'),
    //客户等级2
    'CUSTOMER_LEVEL1'=>array('签订服务协议客户','面谈客户','意向客户','继续跟踪客户','无效客户','无法联系客户'),


    //新的审核流程
    'Role_CHECK'=>array('业务员','团队长','分公司经理','财务'),
    'CHECK_YWY'=>array('业务员','团队长','分公司经理','财务'),
    'CHECK_TDZ'=>array('团队长','分公司经理','财务'),
    'CHECK_FGSZJL'=>array('分公司经理','财务'),
    'ROLE_TDZ' => '团队长',
    'ROLE_FGSJL' => '分公司经理',
    'ROLE'=>array(
        'TDZ'   => '团队长',
        'FGSJL' => '分公司经理',
        'CRMZL' => 'CRM助理',
        'XSBJL' => '销售部经理',
    ),
    //用户缓存存储的时间长度
    'SaveUserInfo'=>5,
    'MaxReceiveCustomerNum'=>100,
    'SALECITY'=>array("上海","北京","深圳","郑州","杭州"),
    'SERVICECITY'=>array("上海","北京","深圳","郑州","杭州","苏州"),

) ;