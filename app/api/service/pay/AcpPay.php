<?php
namespace app\api\service\pay;

/** 银联支付
 * Class PayAlipay
 * @package pay
 */

use app\api\service\Notify;
use unionpay\sdk\AcpService;
use unionpay\sdk\SDKConfig;

class AcpPay{
    protected $config = [
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',				      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000201',				  //业务类型
            'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156
            'merId' => '777290058171411',		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
    ];


    public function index($order){
        $this->config['version'] = SDKConfig::getSDKConfig()->version;//版本号
        $this->config['frontUrl'] = SDKConfig::getSDKConfig()->frontUrl;//前台通知地址
        $this->config['backUrl'] = SDKConfig::getSDKConfig()->backUrl;//后台通知地址
        $this->config['signMethod'] = SDKConfig::getSDKConfig()->signMethod;//签名方法
        $this->config['txnTime'] = date('YmdHis');// 订单发送时间
        $this->config['payTimeout'] = date('YmdHis', strtotime('+15 minutes'));// 超时时间
        $this->config['riskRateInfo'] = '{commodityName='.$order['riskRateInfo'].'}';// 商品名称
        $this->config = array_merge($this->config,$order);
        AcpService::sign ( $this->config );
        $uri = SDKConfig::getSDKConfig()->frontTransUrl;
        $html_form = AcpService::createAutoFormHtml( $this->config, $uri );
        return $html_form;
    }

    /** 验签  银联后台回调
     * @param $result
     */
    public function notify(){
        $data = input('post.');
        try{
            $res = AcpService::validate($data);
            if($res==1){
                if($data['respCode']==00){
                    (new Notify)->ylOrderUpdateStatus(
                        $this->config['merId'],
                        $data['merId'],
                        $data['orderId'],
                        $data['queryId'],
                        $data['txnAmt'],
                        $data['txnTime']
                    );
                }
            }
            file_put_contents(time().'.log',$data);
        }catch (\Exception $e){

        }
//        echo json(['http' => '200','code' => '200','msg' => 'ok']);exit();
        return true;
    }

    /**
     * 银联前台回调
     */
    public function frontReceive()
    {
        $data = input('post.');
        try{
            $res = AcpService::validate($data);
            if($res==1){
                $resFind = $this->findOrder($data['orderId'],$data['txnTime']);
                if($resFind==true){
                    return true;
                }
            }
        }catch (\Exception $e){

        }
    }


    /** 查询订单
     * @param $result
     */
    public function findOrder($orderId,$txnTime){
        $params = array(
            'version' => SDKConfig::getSDKConfig()->version,		  //版本号
            'encoding' => 'utf-8',		  //编码方式
            'signMethod' => SDKConfig::getSDKConfig()->signMethod,		  //签名方法
            'txnType' => '00',		      //交易类型
            'txnSubType' => '00',		  //交易子类
            'bizType' => '000000',		  //业务类型
            'accessType' => '0',		  //接入类型
            'channelType' => '07',		  //渠道类型

            'orderId' => $orderId,
            'merId' => '777290058171411',
            'txnTime' => $txnTime,
        );

        AcpService::sign ( $params ); // 签名
        $url = SDKConfig::getSDKConfig()->singleQueryUrl;
        $result_arr = AcpService::post ( $params, $url);

        if (!AcpService::validate ($result_arr) ){
            return false;
        }
        if ($result_arr["respCode"] == "00"){
            if ($result_arr["origRespCode"] == "00"){
                return true;
            } else if ($result_arr["origRespCode"] == "03"
                || $result_arr["origRespCode"] == "04"
                || $result_arr["origRespCode"] == "05"){
                return false;
            } else {
                return false;
            }
        } else if ($result_arr["respCode"] == "03"
            || $result_arr["respCode"] == "04"
            || $result_arr["respCode"] == "05" ){
            return true;
        } else {
            return false;
        }
    }
}