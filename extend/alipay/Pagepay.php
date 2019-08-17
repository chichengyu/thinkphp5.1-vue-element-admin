<?php

namespace alipay;

//use think\Loader;
//
//Loader::import('alipay.pay.service.AlipayTradeService');
//loader::import('alipay.pay.buildermodel.AlipayTradePagePayContentBuilder');
require '../extend/alipay/pay/service/AlipayTradeService.php';
require '../extend/alipay/pay/buildermodel/AlipayTradePagePayContentBuilder.php';

/**
* 电脑网站支付(扫码支付或账号支付)
*
* 用法:
* 调用 \alipay\Pagepay::pay($params) 即可
*/
class Pagepay
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
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setSubject($params['subject']);
        $payRequestBuilder->setTotalAmount($params['total_amount']);
        $payRequestBuilder->setOutTradeNo($params['out_trade_no']);

        // 3.获取配置
       // $config = config('alipay');
		$config=alipay_confing();
		//var_dump($config);
        $aop = new \AlipayTradeService($config);

        /**
         * 4.电脑网站支付请求(会自动跳转到支付页面)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $aop->pagePay($payRequestBuilder, $config['return_url'],$config['notify_url']);
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