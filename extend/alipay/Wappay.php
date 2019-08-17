<?php

namespace alipay;

//use think\Loader;

//Loader::import('alipay.pay.service.AlipayWapPayTradeService');
//loader::import('alipay.pay.buildermodel.AlipayTradeWapPayContentBuilder');
require '../extend/alipay/pay/service/AlipayWapPayTradeService.php';
require '../extend/alipay/pay/buildermodel/AlipayTradeWapPayContentBuilder.php';

/**
* 支付宝手机网站支付接口
*
* 用法:
* 调用 \alipay\Wappay::pay($params) 即可
*
*/
class Wappay
{
    /**
     * 主入口
     * @param array  $params 支付参数, 具体如下
     * @param string $params['subject'] 订单标题
     * @param string $params['out_trade_no'] 订单商户号
     * @param float  $params['total_amount'] 订单金额
     */
    public static function pay($params)
    {
        // 1.校检参数
        self::checkParams($params);

        // 2.构造参数
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setSubject($params['subject']);
        $payRequestBuilder->setOutTradeNo($params['out_trade_no']);
        $payRequestBuilder->setTotalAmount($params['total_amount']);
        $payRequestBuilder->setTimeExpress('1m');

        // 3.获取配置
        //$config = config('alipay');
        $config=alipay_confing();//传入支付宝参数
        $payResponse = new \AlipayWapPayTradeService($config);

        // 4.进行请求
        $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    }


    /**
     * 校检参数
     */
    private static function checkParams($params)
    {
        if (empty(trim($params['out_trade_no']))) {
            self::processError('商户订单号必填');
        }

        if (empty(trim($params['subject']))) {
            self::processError('商品标题必填');
        }

        if (floatval(trim($params['total_amount'])) <= 0) {
            self::processError('退款金额为大于0的数');
        }
				
    }

    /**
     * 统一错误处理接口
     * @param  string $msg 错误描述
     */
    private static function processError($msg)
    {
        throw new \think\Exception($msg);
    }
}