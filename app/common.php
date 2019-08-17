<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/** 价格计算
 * @param $price 价格
 * @param bool $mode 计算方式：默认false乘 true除
 * @return float|int
 */
function price($price,$mode=true){
    if ($mode){
        return $price * 1000000;
    }else{
        return $price / 1000000;
    }
}

/** 获取协议http/https
 * @return string
 */
function httpHost()
{
    return $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
}

/** 删除目录并创建新目录 （注意：需要 php 开启禁用函数 system）
 * @param $dir  目录路径
 * @return bool
 */
function del_dir($dir) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $dir;
    if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        $str = "rmdir /s/q " . str_replace('/','\\', $path);
    } else {
        $str = "rm -Rf " . $path;
    }
    // $status 0为成功 1为失败
    system($str,$status);
    $res = mkdir($path, 0777, true);
    if (is_dir($path) && $status==1) {
        return false;
    }
    if ($res) {
        return true;
    }
    return false;
}

/**
 *
 */
if(!function_exists('md6')){
    function md6($pwd,$salt){
        return md5(md5($pwd).$salt);
    }
}
/**
 * 发送短信
 */
if(!function_exists('send_sms')){
    function send_sms($tel,$msg){
        $clapi  = new sms\ChuanglanSmsApi();
//        $msg = "【车亿百】您好，您的验证码".$verify;
        //设置您要发送的内容：其中“【】”中括号为运营商签名符号，多签名内容前置添加提交
        $result = $clapi->sendSMS($tel,$msg);

        if(!is_null(json_decode($result))){

            $output=json_decode($result,true);

            if(isset($output['code'])  && $output['code']=='0'){
                return true;
            }else{
                echo $output['errorMsg'];
            }
        }else{
            echo $result;
        }
    }
}

/**
 * 经纬度计算两点之间的距离
 */
if(!function_exists('getDistance')){
    function getDistance($lat1, $lng1, $lat2, $lng2){
        //将角度转为狐度
        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2=deg2rad($lat2);

        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);

        $a=$radLat1-$radLat2;

        $b=$radLng1-$radLng2;

        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;

        return $s;
    }
}

/**
 * 获取客户端真实ip地址
 * @return array|false|string
 */
if(!function_exists('get_real_ip')){
    function get_real_ip(){
        static $realip;
        if(isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }else if(isset($_SERVER['HTTP_CLIENT_IP'])){
                $realip=$_SERVER['HTTP_CLIENT_IP'];
            }else{
                $realip=$_SERVER['REMOTE_ADDR'];
            }
        }else{
            if(getenv('HTTP_X_FORWARDED_FOR')){
                $realip=getenv('HTTP_X_FORWARDED_FOR');
            }else if(getenv('HTTP_CLIENT_IP')){
                $realip=getenv('HTTP_CLIENT_IP');
            }else{
                $realip=getenv('REMOTE_ADDR');
            }
        }
        return $realip;
    }
}

/**
 * 最低月供
 */
if(!function_exists('yuegong')){
    function yuegong($price){
        $yue = (($price-($price*0.1))/48)+((($price-($price*0.1))/48)*0.0052);
        return $yue;
    }
}

//微信支付参数
function wx_confing(){

    //网址
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';////判断http还是https
    $pay_url = $http_type.$_SERVER['HTTP_HOST'];

    $config['APPID']='wx9d9304cfb8c3dcbb';//绑定支付的APPID（必须配置，开户邮件中可查看）
    $config['MCHID']='1522855121';//商户号（必须配置，开户邮件中可查看）
    $config['WXPAY_DEBUG']=false;//开发模式与生产模式 微信支付需要获取用户的真实ip, 本地开发就需要设定一个固定的IP,  处于生产模式, 请务必将次改为false, 用于获取用户真实IP!!!!
    $config['NOTIFY_URL']=$pay_url.'/notify.php';//异步通知地址
    $config['KEY']='e5e64ada5521f8024612d4cff456805b';//商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
    $config['APPSECRET']='628a4b099774c17800b9f019ebfe1450';//公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
    //=======【证书路径设置】=====================================
    /**
     * TODO：设置商户证书路径
     * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     * @var path
     */
    $config['SSLCERT_PATH']='/wxpay/cert/apiclient_cert.pem';
    $config['SSLKEY_PATH']='/wxpay/cert/apiclient_key.pem';
    //=======【curl代理设置】===================================
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    $config['CURL_PROXY_HOST']="0.0.0.0";//"10.152.18.220";
    $config['CURL_PROXY_PORT']=0;//8080;
    //=======【上报信息配置】===================================
    /**
     * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @var int
     */
    $config['REPORT_LEVENL']=1;//
    // ======【日志文件目录】===================================
    $config['WXPAY_LOG']='/wxpay';//

    return $config;
}