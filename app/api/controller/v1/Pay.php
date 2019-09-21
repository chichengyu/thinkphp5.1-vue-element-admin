<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use app\api\service\Pay as PayService;
use app\api\model\StoreOrder as StoreOrderModel;
use app\api\service\pay\PayWechat;
use app\api\service\pay\PayAlipay;
use app\api\service\pay\AcpPay;


class Pay extends Base{
    const PAY_TYPE_KEY = 'pay_type';
    const RAND = 'rand';

    /** 返回支付二维码
     * @param $id
     * @throws \app\lib\exception\ParamsException
     */
    public function getPreOrder($id){
        (new IDMustValidate())->goCheck();
        // payType 1微信支付 2支付宝支付 3银联支付
        $payType = input('get.payType');
        $rand = mt_rand(1000,9999);
        if (!isset($payType)){
            return $this->responseError('请选择支付类型！');
        }
        session(self::PAY_TYPE_KEY,$payType);
        session(self::RAND,$rand);
        StoreOrderModel::where('id',$id)->setField('pay_channel',$payType);
        $pay = new PayService($id,$this->userInfo,$payType);
        return $this->responseSuccess('success',$pay->pay($rand));
    }

    /**
     *  微信支付回调
     */
    public function receiveNotifyWechat(){
        (new PayWechat())->notify();
    }

    /**
     *  支付宝支付回调
     */
    public function receiveNotifyAli(){
        (new PayAlipay())->notify();
    }

    /**
     * 银联支付后台回调
     */
    public function receiveNotifyAcp(){
        (new AcpPay())->notify();
    }

    /**
     * 银联支付前台回调
     */
    public function receiveNotifyAcpTest(){
        $res = (new AcpPay())->frontReceive();
        if($res==true){
            header('Location:http://i55ati.natappfree.cc/#/car/index');
        }else{
            // file_put_contents(time().'.log',123);
        }

    }

    /**
     * ajax轮询检测支付状态并更新数据及跳转
     */
    public function queryOrderJump(){
        $orderNO = input('post.orderNo');
        $orderNO .= session(self::RAND);
        $payType = session(self::PAY_TYPE_KEY);
        if ($payType == 1){// 微信
            $result = (new PayWechat())->find($orderNO);
            if($result){
//            (new Notify())->orderUpdateStatus($orderNO,$result->transaction_id);
                $data = [
                    'return_code' => $result->return_code,
                    'trade_state' => $result->trade_state,
                    'return_msg'  => $result->return_msg,
                ];
                return $this->responseSuccess('success',$data);
            }
        }else if ($payType == 2){// 支付宝
            $result = (new PayAlipay())->find($orderNO);
            if($result){
                if ($result->code = 10000 && $result->trade_status = 'TRADE_SUCCESS'){
                    $data['return_code'] = 'SUCCESS';
                    $data['trade_state'] = 'SUCCESS';
                }
                $data['return_msg'] = $result->msg;
                return $this->responseSuccess('success',$data);
            }
        }
    }
}
