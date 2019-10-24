<?php
namespace app\admin\service\pay;

/** 微信支付
 * Class PayAlipay
 * @package pay
 */

use app\admin\service\Notify;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class PayWechat{
    protected $config = [
        'app_id' => 'wx9d9304cfb8c3dcbb',
        'mch_id' => '1522855121',
        'key' => 'e5e64ada5521f8024612d4cff456805b',
        'notify_url' => 'http://ht.cybcar.cn/api/v1/notify',
//        'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
//        'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
        'log' => [
            'file' => './static/paylog/wechat.log',
            'level' => 'info',
            'type' => 'daily',
            'max_file' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            'verify'  =>  false
        ],
    ];

    /** 支付
     * @param $order
     * @return \Yansongda\Supports\Collection
     */
    public function index($order){
        $pay = Pay::wechat($this->config)->scan($order);
        return $pay;
    }

    /** 验签
     * @param $result
     */
    public function notify(){
        $pay = Pay::wechat($this->config);
        try{
            $data = $pay->verify();
            if ($data && $data->result_code == 'SUCCESS' && $data->return_code == 'SUCCESS'){
                if ($data->out_trade_no && $data->transaction_id && ($data->mch_id == $this->config['mch_id'])){
                    (new Notify())->orderUpdateStatus(
                        $data->appid,
                        $this->config['app_id'],
                        $data->out_trade_no,
                        $data->transaction_id,
                        $data->total_fee,
                        $data->time_end
                    );
                }
            }
            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
//             $e->getMessage();
//            file_put_contents('000.log',$e->getMessage());
        }
        return $pay->success()->send();
    }

    /** 查询订单
     * @param $result
     */
    public function find($orderNO){
        $pay = Pay::wechat($this->config);
        $result = $pay->find($orderNO);
        if ($result && $result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){//返回查询结果集
            return $result;
        }
        return false;
    }

    /** 退款
     * @param $params
     */
    public function refund($params){
        try{
            $path = dirname(__FILE__);
            $this->config['cert_client'] = $path.'/cert/wechat/apiclient_cert.pem';
            $this->config['cert_key'] = $path.'/cert/wechat/apiclient_key.pem';
            $pay = Pay::wechat($this->config);
            $result = $pay->refund($params);
            if ($result && $result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
                return true;
            }
            return false;
        }catch (\Exception $e){
            return false;
        }

    }
}