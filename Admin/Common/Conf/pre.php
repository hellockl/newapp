<?php
/**
 * 生产环境配置.
 * User: Jie
 * Date: 2016/6/1
 * Time: 9:37
 */

return array(
    //生产测试库
    'DB_DEPLOY_TYPE' => 0,
    'DB_RW_SEPARATE' => true,
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'rm-bp15b2opm1zh7e6t8.mysql.rds.aliyuncs.com',
    'DB_PORT' => '3306',
    'DB_NAME' => 'fjscrm_sale',
    'DB_USER' => 'crm1',
    'DB_PWD' => '123456',
    'DB_PREFIX' => '',

    'ROLE_TDZ' => '团队长',
    'ROLE_FGSJL' => '分公司经理',
    //生产数据库连接配置
    /*'DB_DEPLOY_TYPE' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE' => true, // 数据库读写是否分离 主从式有效
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '10.45.23.33,10.47.97.207', // 第一个ip是master的数据库ip, 第二个是slave的
    'DB_PORT' => '3306,3306',
    'DB_NAME' => 'fjscrm_sale,fjscrm_sale',
    'DB_USER' => 'fjsprdu,fjsprdu',
    'DB_PWD' => 'fjsCRM2015,fjsCRM2015',
    'DB_PREFIX' => '',*/

//数据库连接配置结束
    'AUTOCLEAN'=> true,//是否每天清洗 ture 每天清洗  false  每周清洗
    'URL_CASE_INSENSITIVE' =>false,
    //基础服务接口,登录接口
    'BASE_LOGIN' => 'http://pre-base.fang-crm.com/Api/Index/userLogin?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取用户信息接口   $arr=array('where'=>$where,'type'=>$type);
    'BASE_getUserInfo'=>'http://pre-base.fang-crm.com/Api/Index/getUserInfo?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取角色信息  $arr=array('name'=>$name,'value'=>$value);
    'BASE_getRoleInfo'=>'http://pre-base.fang-crm.com/Api/Index/getRoleInfo?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取配置信息  $arr=array('key'=>$key,'value'=>$value);
    'BASE_getConfig'=>'http://pre-base.fang-crm.com/Api/Index/getConfig?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取分公司业务员  $arr=array('city'=>$city);
    'BASE_getAllSalesman'=>'http://pre-base.fang-crm.com/Api/Index/getAllSalesman?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取分公司业务员  $arr=array('city'=>$city);
    'BASE_updateAllSalesman'=>'http://pre-base.fang-crm.com/Api/Index/updateAllSalesman?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //批量获取用户姓名   $arr=array('user_ids'=>$user_ids);
    'BASE_getUserNames'=>'http://pre-base.fang-crm.com/Api/Index/getUserNames?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //根据条件查询部门信息  $arr=array('where'=>$where,"type"=>$type);
    'BASE_getDepartmentByWhere'=>'http://pre-base.fang-crm.com/Api/Index/getDepartmentByWhere?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取来源渠道
    'base_getBrand' => 'http://pre-base.fang-crm.com/Api/Index/getBrand?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //修改个人信息
    'BASE_editUser'=>'http://pre-base.fang-crm.com/Api/Index/editUser?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //修改密码
    'BASE_editPassword'=>'http://pre-base.fang-crm.com/Api/Index/editPassword?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取下属部门列表
    'BASE_getSubDepartmentList'=>'http://pre-base.fang-crm.com/Api/Index/getSubDepartmentList?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取下属部门id
    'BASE_getSubDepartmentId'=>'http://pre-base.fang-crm.com/Api/Index/getSubDepartmentId?key=356o192c191db04c54513b0lc28d46ee63954iab',

    //获取下级业务员
    'BASE_getSubUserByUserId'=>'http://pre-base.fang-crm.com/Api/Index/getSubUserByUserId?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取所在城市
    'BASE_getCityByUserid'=>'http://pre-base.fang-crm.com/Api/Index/getCityByUserid?key=356o192c191db04c54513b0lc28d46ee63954iab',

    //获取中间表sale_task的数据 根据user_id 昨天到开始的所有数据
    'REPORT_getSaleTask' => 'http://pre-report.fang-crm.com/Api/SaleTask/getSaleTask?key=356a192b1913b04c54574d18c28d46e6395428ab',

    'API_URl'=>'http://pre-service.fang-crm.com/Home/Api/Api?key=356aa92b71o3a06c51534d18c75u46e63as428ab',
    'OCDC_URl'=>'http://pre-ocdc.fang-crm.com/Api/Index/save?key=356a192b7oo3b06c54574d18c28d46e63as428ab',
    'OCDC_CALLBACK_URl'=>'http://pre-ocdc.fang-crm.com/Api/Index/pushCallback?key=356a192b7oo3b06c54574d18c28d46e63as428ab',
    //查看客户保留时间
    'LOOK_RETAIN_TIME'=>15,
    //客户被查看上限
    'CUSTOMER_UP_LOOK_NUM'=>2,

    'UPLOAD_SERVICE_URL'=>'http://192.168.1.111:96/',
    'UPLOAD_KEY'=>'356a192b7913b04c54574d18c28d46e6395428ab',
    'IMAGE_PATH'=>"http://pre-sale.fang-crm.com",

    //自营审核循环数
    'SELF_RECEIVABLES_NUM'=>2,
    //客户保留数量
    'maxCustomerNum'=>50,
    //体现客户的状态
    'customer_classify'=>array('正常','待联系','空号','未接','已接无需求','无效'),

    //Redis
    'REDIS' => array(
        'host' => '10.45.23.33',
        'port' => 6379,
    ),
//其它配置
    'SHOW_PAGE_TRACE' => false,
    'OPEN_KEYS' => array(
        '356a192b1913b04c54574d18c28d46e6395428ab' => array(
            'id'   => 1,
            'name' => 'ocdc',
            'ip'   => array(),
        ),
        '356a192b7913b06c54574d18c28d46e6395428ab' => array(
            'id'   => 2,
            'name' => 'pc',
            'ip'   => array(),
        ),
        'mvpa192b7913b06c54574d18c28d46e63954issb' => array(
            'id' => 3,
            'name' =>'mobile',
            'ip'   => array(),

        ),
    )
);