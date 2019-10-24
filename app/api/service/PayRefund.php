<?php
namespace app\admin\service;

use app\admin\service\pay\PayAlipay;
use app\admin\service\pay\PayWechat;
use app\admin\service\pay\AcpPay;
use app\common\model\CarNumber;
use app\admin\service\eunm\OrderStatus;
use app\admin\service\eunm\PayType;
use app\common\model\StoreOrder;
use app\common\model\StoreOwner;
use app\lib\exception\OrderException;

class Pay{

    /** 退款
     * @param $id
     * @throws OrderException
     */
    public function index($id){
        list($owner,$order) = $this->chechOwnerID($id);
        $result = $this->sendRefund($order,$owner);
        if ($result){
            $this->updateCarStock($owner);
        }
        return $result;
    }

    /** 更新库存
     * @param $owner
     */
    public function updateCarStock($owner){
        CarNumber::where('id',$owner->car_number_id)->setInc('car_number',$owner->count);
        StoreOwner::where('id',$owner->id)->setField('order_status',-3);
        //StoreOrder::Where('id',$owner->store_order_id)->setField('after_status',3);
    }

    /** 发送退款
     * @param $order
     * @param $owner
     */
    public function sendRefund($order,$owner){
        $scale = 0.2;
        if ($owner->price_jxs > 20000000){
            $scale = 0.5;
        }
        $refundAmount = price($scale) * $owner->count;// 退款金额
        if ($order->pay_channel == PayType::WEICHAT){// 微信
            $params = [
                'transaction_id' => $order->transaction_id, // 商户订单号 或 微信订单号 二选一
                'out_refund_no' => $owner->order_no_sub, // 退款单号
                'total_fee' => $order->order_amount_total,
                'refund_fee' => $refundAmount,
            ];
            return (new PayWechat())->refund($params);
        }else if ($order->pay_channel == PayType::ALI){// 支付宝
            $params = [
                'trade_no' => $order->transaction_id,// 支付宝流水号
                'out_request_no' => $owner->order_no_sub,// 退款单号
                'refund_amount' => $refundAmount / 100,// 退款金额
            ];
            return (new PayAlipay)->refund($params);
        }else if ($order->pay_channel == PayType::ACP){// 银联
            $params = [
                'origQryId' => $order->transaction_id, // 银联流水号
                'orderId' => $owner->order_no_sub, //  退款单号 商户订单号码
                'txnAmt' => $refundAmount, //退款金额
            ];
            return (new AcpPay())->index($params);
        }
    }

    /** 订单检测
     * @param $id  车主id
     * @return array
     */
    public function chechOwnerID($id){
        $owner = StoreOwner::field('so.*,cd.price_jxs')
                ->alias('so')
                ->join('CarDetails cd','so.car_id=cd.id')
                ->where('so.id',$id)
                ->find();
        if (!$owner){
            throw new OrderException(['msg' => '订单不存在']);
        }
        if ($owner->order_status != OrderStatus::REFUND){
            throw new OrderException(['msg' => '订单未申请退款']);
        }
        $order = $this->checkOrderID($owner->store_order_id);
        return [$owner,$order];
    }

    /** 退款前的验证检测
     * @return bool
     */
    private function checkOrderID($orderID){
        $order = StoreOrder::where('id',$orderID)->find();
        if (!$order){
            throw new OrderException(['msg' => '订单不存在']);
        }
        if ($order->order_status == OrderStatus::UNPAID){
            throw new OrderException(['msg' => '订单未支付']);
        }
        return $order;
    }
}