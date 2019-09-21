<?php
namespace app\api\service;

use app\api\model\StoreOrder;
use app\api\service\eunm\OrderStatus;
use app\api\service\eunm\PayType;
use app\api\service\pay\AcpPay;
use app\api\service\pay\PayAlipay;
use app\api\service\pay\PayWechat;
use app\lib\exception\OrderException;

class Pay{
    private $orderID;
    private $order;
    private $userInfo;
    private $payType;

    /**
     * Pay constructor.
     * @param $orderID  订单id
     * @param $userInfo 当前用户信息
     * @param $payType  支付方式
     * @throws OrderException
     */
    public function __construct($orderID,$userInfo,$payType){
        if (empty($orderID)){
            throw new OrderException(['msg' => '订单号不允许为NULL']);
        }
        $this->orderID = $orderID;
        $this->userInfo = $userInfo;
        $this->payType = $payType;
    }

    /** 支付
     * @param $random 随机数
     * @throws OrderException
     */
    public function pay($rand){
        $this->checkOrderID();
        // 检测库存
        (new Order)->payCheckStack($this->orderID);
        // 发送订单
        return $this->sendOrder($rand);
    }

    /** 发送支付订单
     * @return string
     */
    private function sendOrder($rand){
        if ($this->payType == PayType::ACP){ // 银联支付
            $order = [
                'orderId' => $this->order->order_no.$rand,// //商户订单号
                'txnAmt' => $this->order->order_amount_total,// 金额 分
                'riskRateInfo' => '测试商品名称' // 商品名称
            ];
            $html = (new AcpPay())->index($order);
            return ['html' => $html];
        }else{
            $commonOrder = [
                'out_trade_no' => $this->order->order_no.$rand,
//            'out_trade_no' => $this->order->order_no,
            ];
            $qrcode = '';
            if ($this->payType == PayType::WEICHAT){// 微信支付
                $orderWeixin = [
                    'total_fee' => $this->order->order_amount_total, // **单位：分**
                    'body' => '车亿百（深圳）信息科技有限公司',
                    'product_id' => $this->order->store_id,
                    'time_expire' => date('YmdHis',time()+30*60),
                ];
                $result = (new PayWechat())->index(array_merge($commonOrder,$orderWeixin));
                $qrcode = $result->code_url;
            }else if ($this->payType == PayType::ALI){// 支付宝支付
                $orderAli = [
                    'total_amount' => $this->order->order_amount_total / 100, // **单位：元**
                    'subject' => '车亿百（深圳）信息科技有限公司',
                    'timeout_express' => '30m',
                ];
                $result = (new PayAlipay())->index(array_merge($commonOrder,$orderAli));
                $qrcode = $result->qr_code;
            }
            return ['qrcode' => $qrcode];
        }
    }

    /** 支付前的验证检测
     * @return bool
     */
    private function checkOrderID(){
        $order = StoreOrder::where('id',$this->orderID)->find();
        if (!$order){
            throw new OrderException(['msg' => '订单不存在']);
        }
        if ($order->store_id != $this->userInfo->store_id){
            throw new OrderException(['msg' => '订单与用户不匹配']);
        }
        if ($this->userInfo->status != 1){
            throw new OrderException(['msg' => '门店负责人才能支付']);
        }
        if ($order->order_status != OrderStatus::UNPAID){
            throw new OrderException(['msg' => '订单已支付']);
        }
        $this->order = $order;
        return true;
    }
}