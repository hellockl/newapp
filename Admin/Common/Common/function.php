<?php
/**
 * Created by PhpStorm.
 * User: Jie
 * Date: 2016/7/14
 * Time: 13:35
 */

/**
 * 获取页面内容
 * @author JIE <xuhuijie@fangjinsuo.com>>
 * @param string $url : 页面地址
 * @param int $timeout : 超时
 * @return mixed: 页面内容
 */
function get_url($url, $timeout = 10)
{
    if (function_exists('curl_init')) {     // 服务器支持curl
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curlHandle, CURLOPT_HEADER, FALSE);    // 显示header
        curl_setopt($curlHandle, CURLOPT_NOBODY, FALSE);    // 不显示body
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);    // 超时
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, TRUE); // 重定向
        curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 10);    // 最大跳转次数
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
        curl_setopt($curlHandle, CURLOPT_USERAGENT, "Chrome/49.0.2623.87");  // 模拟浏览器
        $result = curl_exec($curlHandle);
        curl_close($curlHandle);
    } else {                                // 服务器不支持curl
        $ctx = stream_context_create(array(
            'http' => array(
                'method' => "GET",
                'header' => "Content-Type: text/html; charset=utf-8",
                'timeout' => $timeout
            )
        ));
        $result = file_get_contents($url, 0, $ctx);
    }
    return $result;
}

/**
 * post提交
 * @author JIE <xuhuijie@fangjinsuo.com>
 * @param string $url : 提交地址
 * @param array $data : 提交的数据，$data = array('A' => '1', 'B' => '2');
 * @param int $timeout : 超时
 * @return mixed: 返回信息
 */
function post_url($url, $data, $timeout = 10)
{
    $curlHandle = curl_init(); // 启动一个CURL会话
    curl_setopt($curlHandle, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curlHandle, CURLOPT_HEADER, FALSE);    // 显示header
    curl_setopt($curlHandle, CURLOPT_NOBODY, FALSE);    // 不显示body
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);    // 超时
    curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, TRUE); // 重定向
    curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 20);    // 最大跳转次数
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式返回
    curl_setopt($curlHandle, CURLOPT_USERAGENT, "Chrome/49.0.2623.87");  // 模拟浏览器
    curl_setopt($curlHandle, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($data)); // Post提交的数据包
    $result = curl_exec($curlHandle); // 执行操作
    curl_close($curlHandle);
    return $result;
}

function https_request($url, $data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

//手机号码加密
function phone_encode($string = '')
{
    if(is_numeric($string) && $string){
        return \Think\Crypt\Driver\Crypt :: newencrypt($string,C('TELEPHONE_SECRET_KEY'));
    }
    return $string;
}

/*
 * UPDATE customer SET
 * telephonenumber =
 * REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephonenumber,"2","@#$"),"4","%^&"),"6","*()"),"3","abcd"),"8","efg"),"5","!!"),"9","+-")
 *
 * 手机号码解密
 * @param string 密文
 * @param bool $safe 是否隐藏号码输出；默认false
 * @return string 
 */
function phone_decode($string = '',$safe = false)
{
    $phone = $string;
    if(!is_numeric($string) && $string){
        $phone =  \Think\Crypt\Driver\Crypt :: newdecrypt($string,C('TELEPHONE_SECRET_KEY'));
    }
    if($safe)
        return safePhoneNumber($phone);
    return $phone;
}
/*
 * 解析DatePicker 出来的区间时间；
 * @param string $rangeDate
 * @retun array
 */
function splitDate($rangeDate=''){
    $rangeDate = str_replace('+-+',' - ',$rangeDate);
    $date = explode(' - ',$rangeDate);
    return array(strtotime($date[0]),strtotime($date[1].' 23:59:59'));
}

function getUploadeToken(){
    $ret = get_url(C('UPLOAD_SERVICE_URL').'file/getToken?expires=120&key='.C('UPLOAD_KEY'));
    $data = json_decode($ret);

    $token = $data->retData->token;
    return $token;
}
function getCity($city_id = 0){
    $ret_spt = get_url('http://api.fangjinsuo.com/idcarea/listAreas/'.$city_id);
    $ret_spt = json_decode($ret_spt,true);
    $result = $ret_spt;
    if($city_id === 0){
        $result['retData'] = '<option value="">--请选择省份--</option>';
    }elseif($city_id%10000 == 0){
        $result['retData'] = '<option value="">--请选择城市--</option>';
    }else{
        $result['retData'] = '<option value="">--请选择地区--</option>';
    }
    if($ret_spt['errNum'] == 0){
        foreach ($ret_spt['retData'] as $k=>$v){
            $result['retData'] .= '<option value="'.$v['id'].'" >'.$v['name'].'</option>';
        }
    }
    return $result;
}
/**
 * 隐藏手机号码
 * @param int $phone 手机号码
 * @return string 安全的手机号码
 * @author jie
 */
function safePhoneNumber($phone){
    if(strlen($phone) == 11)
        return substr($phone, 0 , 7)."****";
    return $phone;
}
