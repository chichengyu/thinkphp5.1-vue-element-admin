<?php
namespace app\api\service;
use app\api\model\CarNumber;
use app\api\model\StoreOrder;
use app\api\model\StoreOwner;
use app\api\model\CarDetails;
use app\api\service\eunm\OrderStatus;
use app\lib\ResponseJson;
use think\Db;

class Notify{
    use ResponseJson;

    /** 银联支付 调用 更新状态
     * @param $payAappid
     * @param $appid 银联商户id
     * @param $orderNO  订单编号
     * @param $transactionId  流水号id
     * @param $money  金额
     * @param $payTime 交易时间
     * @return bool
     */
    public function ylOrderUpdateStatus($payAappid,$appid,$orderNO,$transactionId,$money,$payTime){
        Db::startTrans();
        try{
            // 更新状态
            $order = StoreOrder::where('order_no',substr($orderNO,0,16))->lock(true)->find();
            if (($order->order_status == OrderStatus::UNPAID) &&
                ($payAappid == $appid) &&
                ($money == $order->order_amount_total)){
                // 更新数据
                $order->order_status = OrderStatus::PAID;
                $order->transaction_id = $transactionId;
                $order->pay_time = strtotime($payTime);
                $order->save();
                // 减库存
                $this->orderUpdateStock($order);
                Db::commit();
                return true;
            }
            return false;
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
    }

    /** 更新订单状态 (微信、支付宝)
     * @param $payAappid 支付成功返回的参数中的data中的appid
     * @param $data      appid
     * @param $orderNO   商家订单编码号
     * @param $transactionId 第三方订单号
     * @param $money 支付的金额（data中返回）
     * @param  $payTime 支付时间（data中返回）
     */
    public function orderUpdateStatus($payAappid,$appid,$orderNO,$transactionId,$money,$payTime){
        Db::startTrans();
        try{
            // 更新状态
            $order = StoreOrder::where('order_no',substr($orderNO,0,16))->lock(true)->find();
            if (($order->order_status == OrderStatus::UNPAID) &&
                ($payAappid == $appid) &&
                ($money == $order->order_amount_total)){
                // 更新数据
                $order->order_status = OrderStatus::PAID;
                $order->transaction_id = $transactionId;
                $order->pay_time = strtotime($payTime);
                $order->save();
                // 减库存
                $this->orderUpdateStock($order);
                Db::commit();
                return true;
            }
            return false;
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
    }



    /** 更新库存
     * @param $order 订单数据
     */
    private function orderUpdateStock($order){
        $ownerOrder = StoreOwner::where('store_order_id',$order->id)->select();
        $products = self::getProductByOrder($ownerOrder);
        foreach ($products as $v){
            $this->getCheckOrderStackByOrderID($v,$ownerOrder);
        }
    }

    /** 更新库存
     * @param $car
     * @param $order
     * @throws \think\Exception
     */
    private function getCheckOrderStackByOrderID($car,$order){
        $count = 0;
        $index = 0;
        foreach ($car['car_stock'] as $key=>$val){
            foreach ($order as $v){
                if ($v['car_id'] == $car['id'] && $val['id'] == $v['car_number_id']){
                    $index = $key;
                    $count += $v['count'];
                }
            }
        }
        // 减库存
        CarNumber::where('id',$car['car_stock'][$index]['id'])->setDec('car_number',$count);
        // 已售数量
        CarDetails::where('id',$car['car_stock'][$index]['car_id'])->setInc('print',$count);
    }

    /** 查询商品真实数据
     * @param $products
     * @return mixed
     * @throws \think\Exception\DbException
     */
    private function getProductByOrder($products){
        $ids = [];
        foreach ($products as $product) {
            array_push($ids,$product['car_id']);
        }
        $cares = model('CarDetails')::with('carStock')
            ->all($ids)
            ->visible(['id','name','thumbnail','price_jxs','car_stock'])
            ->toArray();
        return $cares;
    }
}