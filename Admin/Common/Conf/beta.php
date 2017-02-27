<?php
/**
 * beta环境配置.
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
    'URL_CASE_INSENSITIVE' =>false,

    'ROLE_TDZ' => '团队长',
    'ROLE_FGSJL' => '分公司经理',
    //基础服务接口,登录接口
    'BASE_LOGIN' => 'http://beta-base.fang-crm.com/Api/Index/userLogin?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取用户信息接口   $arr=array('where'=>$where,'type'=>$type);
    'BASE_getUserInfo'=>'http://beta-base.fang-crm.com/Api/Index/getUserInfo?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取角色信息  $arr=array('name'=>$name,'value'=>$value);
    'BASE_getRoleInfo'=>'http://beta-base.fang-crm.com/Api/Index/getRoleInfo?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取配置信息  $arr=array('key'=>$key,'value'=>$value);
    'BASE_getConfig'=>'http://beta-base.fang-crm.com/Api/Index/getConfig?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取分公司业务员  $arr=array('city'=>$city);
    'BASE_getAllSalesman'=>'http://beta-base.fang-crm.com/Api/Index/getAllSalesman?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //读取分公司业务员  $arr=array('city'=>$city);
    'BASE_updateAllSalesman'=>'http://beta-base.fang-crm.com/Api/Index/updateAllSalesman?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //批量获取用户姓名   $arr=array('user_ids'=>$user_ids);
    'BASE_getUserNames'=>'http://beta-base.fang-crm.com/Api/Index/getUserNames?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //根据条件查询部门信息  $arr=array('where'=>$where,"type"=>$type);
    'BASE_getDepartmentByWhere'=>'http://beta-base.fang-crm.com/Api/Index/getDepartmentByWhere?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取来源渠道
    'base_getBrand' => 'http://beta-base.fang-crm.com/Api/Index/getBrand?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //修改个人信息
    'BASE_editUser'=>'http://beta-base.fang-crm.com/Api/Index/editUser?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //修改密码
    'BASE_editPassword'=>'http://beta-base.fang-crm.com/Api/Index/editPassword?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取下属部门列表
    'BASE_getSubDepartmentList'=>'http://beta-base.fang-crm.com/Api/Index/getSubDepartmentList?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取下属部门id
    'BASE_getSubDepartmentId'=>'http://beta.base.fang-crm.com/Api/Index/getSubDepartmentId?key=356o192c191db04c54513b0lc28d46ee63954iab',

    //获取下级业务员
    'BASE_getSubUserByUserId'=>'http://beta-base.fang-crm.com/Api/Index/getSubUserByUserId?key=356o192c191db04c54513b0lc28d46ee63954iab',
    //获取所在城市
    'BASE_getCityByUserid'=>'http://beta-base.fang-crm.com/Api/Index/getCityByUserid?key=356o192c191db04c54513b0lc28d46ee63954iab',

    //获取中间表sale_task的数据 根据user_id 昨天到开始的所有数据
    'REPORT_getSaleTask' => 'http://beta-report.fang-crm.com/Api/SaleTask/getSaleTask?key=356a192b1913b04c54574d18c28d46e6395428ab',


    'API_URl'=>'http://beta-service.fang-crm.com/Home/Api/Api?key=356aa92b71o3a06c51534d18c75u46e63as428ab',
    'OCDC_URl'=>'http://beta-ocdc.fang-crm.com/Api/Index/save?key=366a192b7w17e14c54574d18c28d48e6123428ab',
    'OCDC_CALLBACK_URl'=>'http://beta-ocdc.fang-crm.com/Api/Index/pushCallback?key=366a192b7w17e14c54574d18c28d48e6123428ab',
    //查看客户保留时间
    'LOOK_RETAIN_TIME'=>15,
    //客户被查看上限
    'CUSTOMER_UP_LOOK_NUM'=>2,

    'UPLOAD_SERVICE_URL'=>'http://192.168.1.111:96/',
    'UPLOAD_KEY'=>'356a192b7913b04c54574d18c28d46e6395428ab',
    'IMAGE_PATH'=>"http://beta-sale.fang-crm.com",



    //自营审核循环数
    'SELF_RECEIVABLES_NUM'=>2,
    //客户保留数量
    'maxCustomerNum'=>50,
    //体现客户的状态
    'customer_classify'=>array('正常','待联系','空号','未接','已接无需求','无效'),

    //Redis
    'REDIS' => array(
        'host' => '192.168.1.112',
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