<?php
namespace app\api\service;

use app\api\model\StoreOrder as OrderModel;
use app\api\model\StoreOwner as StoreOwnerModel;
use app\lib\exception\OrderException;
use think\Db;

class Order{

    protected $oProducts;// 用户提交的商品订单
    protected $products;// 商品的真实信息
    protected $store_id;// 门店id
    protected $payType;// 支付方式 0全款(10%) 1垫付(30%)


    /** 外部（支付）调用订单与库存检测
     * @param $orderID  订单id
     */
    public function payCheckStack($orderID){
        $order = StoreOwnerModel::where('store_order_id',$orderID)->select();
        if (!$order){
            throw new OrderException(['msg' => '订单不存在！']);
        }
        $products = self::getProductByOrder($order);
        foreach ($products as $v){
            $this->getCheckOrderStackByOrderID($v,$order);
        }
        return true;
    }

    /** 支付前的库存检测
     * @param $car  当前车辆
     * @param $order 订单
     * @throws OrderException
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
        if (($car['car_stock'][$index]['car_number'] - $count) < 0){
            throw new OrderException(['msg' => $car['name'].'的车辆'.$car['car_stock'][$index]['car_color'].'库存不够，支付失败，请重新下单.']);
        }
    }

    /** 下单
     * @param $storeID 门店id
     * @param $payType 支付方式
     * @param $products 提交的商品信息
     */
    public function place($storeID,$payType,$products){
        $this->oProducts = $products;
        $this->payType = $payType;
        $this->products = $this->getProductByOrder($products);
        $this->store_id = $storeID;

        // 检测订单所有商品，并返回相关信息
        $status = $this->getOrderStatus();
        // 入库
        $status = $this->createOrder($status);
        return $status;
    }

    /** 订单入库
     * @param $status
     */
    private function createOrder($status){
        Db::startTrans();
        try{
            $orderNo = $this->makeOrderNo();
            $orderModel = new OrderModel;
            $orderModel->store_id = $this->store_id;
            $orderModel->order_no = $orderNo;
            $orderModel->count = $status['totalCount'];
            $orderModel->total_price = $status['orderPrice'];
            $orderModel->order_amount_total = $status['orderAmountTotal'];
            $orderModel->pay_type = $this->payType;
            $orderModel->create_time = time();
            if ($orderModel->save()){
                foreach ($status['ownerAddData'] as &$v){
                    $v['store_order_id'] = $orderModel->id;
                    $v['order_no_sub'] = $this->makeOrderNoSub();
                }
                $storeOwnerModel = new StoreOwnerModel;
                $storeOwnerModel->allowField(true)->saveAll($status['ownerAddData']);
                Db::commit();
                return [
                    'orderNo' => $orderNo,// 订单号
                    'order_id' => $orderModel->id,// 订单id
                    'create_time' => $orderModel->create_time,// 下单时间
                    'order_amount_total' => $status['orderAmountTotal'] // 实际应付价格
                ];
            }
        }catch (\Exception $e){
            Db::rollback();
            throw new OrderException();
        }
    }

    /**
     * 对订单列表的所有车辆进行检测
     */
    private function getOrderStatus(){
        // 顶义订单列表返回数据的默认值
        $carOrderStatus = array(
            'orderPrice' => 0,// 订单列表所有商品的总价格
            'orderAmountTotal' => 0,// 订单实际付款总价格
            'totalCount' => 0,// 总共买了几件商品(包括同样的商品)
            'carOrderArray' => array(), // 所有检测通过的订单列表的每件商品的详细信息,默认空数组
            'ownerAddData' => array() // 车主数据
        );
        foreach ($this->oProducts as $v){
            $status = $this->getProductStatus($v['car_id'],$v['car_number_id'],$v['count'],$this->products);
            if ($status['haveStock']){
                $carOrderStatus['orderPrice'] += $status['totalPrice'];
                $carOrderStatus['orderAmountTotal'] += $status['orderAmountTotal'];
                $carOrderStatus['totalCount'] += $status['counts'];
                $carOrderStatus['carOrderArray'][] = $status;
                foreach ($v['owner'] as &$val){
                    $val['car_id'] = $v['car_id'];
                    $val['car_id'] = $v['car_id'];
                    $val['car_number_id'] = $v['car_number_id'];
                }
                $carOrderStatus['ownerAddData'] = array_merge($carOrderStatus['ownerAddData'],$v['owner']);
            }
        }
        return $carOrderStatus;
    }

    /** 当前商品检测
     * @param $carID  车辆id
     * @param $carNumberID  车辆属性id
     * @param $count  购买的数量
     * @param $products  车辆真实信息
     */
    private function getProductStatus($carID,$carNumberID,$count,$products){
        // 定义当前这件商品的信息默认值
        $currentStatus = array(
            'id' => null,// 当前检测的商品id
            'haveStock' => false,// 当前商品的库存量是否足够，默认不够
            'counts'=> 0,// 购买当前商品的数量，总数量
            'name' => '',// 当前商品名称
            'totalPrice' => 0, // 计算购买当前商品的总价
            'orderAmountTotal' => 0, // 实际付款总价
            'thumbnail' => '',// 当前这件商品的图片
        );
        $pIndex = -1;// 用来标记通过检测当前商品id
        for ($i=0,$len=count($products); $i < $len; $i++) {
            if ($carID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        // 判断当前商品是否存在
        if ($pIndex == -1) {
            // 客户端提交的 id 为 car_id 的商品可能不存在
            throw new OrderException(['msg' => 'id为'.$carID.'的车辆不存在，下单失败.']);
        }
        // 判断当前商品库存是否足够
        $p = $products[$pIndex];
        foreach ($p['car_stock'] as $v){
            if ($v['id'] == $carNumberID){
                if (($v['car_number'] - $count) >= 0){
                    $currentStatus['haveStock'] = true;
                }else{
                    throw new OrderException(['msg' => $p['name'].'的车辆'.$v['car_color'].'库存不够，下单失败.']);
                }
            }
        }
        // 存在并检测通过的商品进行计算
        $currentStatus['id'] = $carID;
        $currentStatus['counts'] = $count;
        $currentStatus['name'] = $p['name'];
        $currentStatus['thumbnail'] = httpHost().$_SERVER['HTTP_HOST'].$p['thumbnail'];
        $currentStatus['totalPrice'] = $p['price_jxs'] * $count;
        $scale = 0.1;
        if ($this->payType == 1){
            $scale = 0.3;
        }
        $currentStatus['orderAmountTotal'] = $p['price_jxs'] * $count * $scale;
        return $currentStatus;
    }


    /** 查询商品车辆的真实信息
     * @param $products 用户提交的订单车辆信息
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

    // 生成订单号
    private function makeOrderNo(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }
    // 生成子订单号
    private function makeOrderNoSub() {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $usec), 0 ,4);
        $str = rand(10,99);
        return date("YmdHis").$usec.$str;
    }
}