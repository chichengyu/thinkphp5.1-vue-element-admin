<?php

namespace alipay;

//use think\Loader;

//Loader::import('alipay.pay.service.AlipayTradeService');
require '../extend/alipay/pay/service/AlipayTradeService.php';


/**
 * 功能：支付宝页面跳转同步通知页面
 * 版本：2.0
 * 修改日期：2017-05-01
*
* 用法:
* 调用 \alipay\Pagepay::check($params) 即可
*
*/
class ReturnUrl
{
    /**
     * 支付宝页面跳转同步通知页面校检, 包括验签和数据库信息与通知信息对比
     *
     * @param array  $params 数据库中查询到的订单信息
     * @param string $params['out_trade_no'] 商户订单
     * @param float  $params['total_amount'] 订单金额
     */
    public static function check($params)
    {
        // 1.第一步校检签名
        //$config = config('alipay');
        $config=alipay_confing();//传入支付宝参数
        $alipaySevice = new \AlipayTradeService($config);
        $signResult = $alipaySevice->check(input('post.'));

        // 2.和数据库信息做对比
        $paramsResult = self::checkParams($params);

//      // 3.返回结果
       if($signResult && $paramsResult) {
        //if($signResult) {
            return true;
        } else {
            return false;
        }

//return true;

    }

    /**
     * 判断两个数组是否一致, 两个数组的参数如下：
     * $params['out_trade_no'] 商户单号
     * $params['total_amount'] 订单金额
     * $params['app_id']       app_id号
     */
    public static function checkParams($params)
    {
        $notifyArr = [
            'out_trade_no' => htmlspecialchars(input('out_trade_no')),
            'total_amount' => htmlspecialchars(input('trade_no')),
            'app_id'       => htmlspecialchars(input('app_id')),
        ];
		//取得系统APPID
		    $appid= alipay_confing();
			$app_id2=$appid['app_id'];
			//dump(htmlspecialchars(input('app_id'))."|".$app_id2);die;
			dump($params);die;
        $paramsArr = [
            'out_trade_no' => $params['out_trade_no'],
            'total_amount' => $params['total_amount'],
            'app_id'       => $app_id2,
        ];
		dump($paramsArr);die;
		if(input('out_trade_no')==$params['out_trade_no']){
			return true;
		}else{
			return false;
		}
		
       // $result = array_diff_assoc($paramsArr, $notifyArr);
        //return empty($result) ? true : false;
		//return true;
    }
}