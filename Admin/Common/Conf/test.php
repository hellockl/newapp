<?php
/**
 * 测试环境配置.
 * User: Jie
 * Date: 2016/6/1
 * Time: 9:37
 */
return array(

    //数据库连接配置
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '192.168.1.110',
    'DB_PORT' => '3306',
    'DB_NAME' => 'fjscrm_sale',
    'DB_USER' => 'devu',
    'DB_PWD' => 'devu888',
    'DB_PREFIX' => '',

    'AUTOCLEAN'=> true,//是否每天清洗 ture 每天清洗  false  每周清洗

    //基础服务接口,登录接口
    'BASE_LOGIN' => 'http://base.fang-crm.com/Api/Index/userLogin?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取用户信息接口   $arr=array('where'=>$where,'type'=>$type);
    'BASE_getUserInfo'=>'http://base.fang-crm.com/Api/Index/getUserInfo?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取角色信息  $arr=array('name'=>$name,'value'=>$value);
    'BASE_getRoleInfo'=>'http://base.fang-crm.com/Api/Index/getRoleInfo?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取配置信息  $arr=array('key'=>$key,'value'=>$value);
    'BASE_getConfig'=>'http://base.fang-crm.com/Api/Index/getConfig?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取分公司业务员  $arr=array('city'=>$city);
    'BASE_getAllSalesman'=>'http://base.fang-crm.com/Api/Index/getAllSalesman?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取来源渠道
    'base_getBrand' => 'http://base.fang-crm.com/Api/Index/getBrand?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取下属部门列表
    'BASE_getSubDepartmentList'=>'http://base.fang-crm.com/Api/Index/getSubDepartmentList?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取下属部门id
    'BASE_getSubDepartmentId'=>'http://base.fang-crm.com/Api/Index/getSubDepartmentId?key=356o192c191db04c54513b0lc28d46ee63954iab',
    'API_URl'=>'http://service.fang-crm.com/Home/Api/Api',

    //获取中间表sale_task的数据 根据user_id 昨天到开始的所有数据
    'REPORT_getSaleTask' => 'http://report.fang-crm.com/Api/SaleTask/getSaleTask?key=356a192b1913b04c54574d18c28d46e6395428ab',

    //查看客户保留时间
    'LOOK_RETAIN_TIME'=>15,
    //客户被查看上限
    'CUSTOMER_UP_LOOK_NUM'=>2,
    //体现客户的状态
    'customer_classify'=>array('正常','待联系','空号','未接','已接无需求','无效'),

//其它配置
    'SHOW_PAGE_TRACE' => false,
);