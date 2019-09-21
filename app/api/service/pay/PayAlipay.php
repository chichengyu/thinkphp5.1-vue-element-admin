<?php
namespace app\api\service\pay;

/** 支付宝
 * Class PayAlipay
 * @package pay
 */

use app\api\service\Notify;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class PayAlipay{
    protected $config = [
        'app_id' => '2019070365764545',
        'notify_url' => 'http://store.cybcar.cn/api/v1/notify_ali',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArAU1h02/tgMo/rrl3pYpBUW6nk6Cl5x5sqV2q89P/NF2MAR3jwNq5+/SrL0vGiVV7U2KFKWy0hqcGE9Ky32S7h3B+XnE/YJy1Vxud2h+gfdfJTYYk33vl9wCPeat+Ebf80+khQIBE/s3Cfylm83JcgMd8BTTITuWlcNAtwShiqWxag4O4Ts/vxvPC8hmRG4dzXglITAZE/7rzccjJvvFItGpOVR6DAab+MY2BiL+NjHrL47Qs0qmnkh4L79dJkwgjSOMSHGJLvL0uMp5jF/1UlymLz6rdrD9lbecuTJGejlo2uhhH1wxtaDQ6K/eLqTzdM5WVvjUTJlLIEoy2aRd+wIDAQAB',
        'private_key' => 'MIIEpgIBAAKCAQEAynMRAjDmasp1alyVUGM91wo8yjyVm9p+Bja/xAa1XxyAu4OAEq848G1YC1SbMIMACQuRwlMuXtc7ZAgkF4EAzmTLH5cqwU0m/E4g3QaEWvoGKbcbs2QlCbQttrIk/x4SlDpSh/m7fh1bd8z3szG0ipCZKuCF55RnWReQpPbx32eFQIk/Zn4/PFWxJ5AiGElp9NuY1HZRS4r9E8OiUnrz1hGubrcEv0apiNHgHRQHxAZdkdB4d+b0QLnAZ3XAhN5RddgsrTfFRbnyi8bMGOZZ0IMxs+UfV+wrg9R+4TIIBXJiDqgcH8LFDZ4OzGKdKjZedu81L02C6XEtGinbd5C56wIDAQABAoIBAQC3OEw8X2UpYPHCwOehSS++XlWF8i60Tpmfpg0Hq8qZuYIIMorGXi556Xu1RWzuCqENjprr87wTuVbWwUdoviesoyvx+y2Go/yUe2Dip/NBIuZQ+pbnU8rxbU+x+Kfi+6R04hOvXCSDjq4pTice1e0/On5kf66wV27xApK1uYhNVhblEq80ni8tIgjhPZeAKeMk4Yi6p3c8oTDYH2trD6lNKXHB0ytZWn6FKJV301zOKREAxQCKdOCTT7Juet8tDgkg27S2zpp0TOwVcMXmClQUtbuhAXwWSs/opY2nQtNkXa5XRU1iIFu4wdHmUAh5Rqnjee9LMFNNFy68aMo/16wBAoGBAOjwoNHG/0OVousm3C8SWUvC5bmuIuv4O3qtfrT1obsDgny/82G/rY7DDvy9b2Pt8UP98kFd/KTeIoS5Q/GDGFL64XH9LS0Dk1Jsaa+Oo0COvHpOhuFqz9BH+V534tBf/ZcU2IvqnzhsY8FE+1o9yIAD2FkVR9eTIIykkA27ohOhAoGBAN59uLC3ZsvQVd0fi04fAA5i1Wyqx3mcaD5Ef5yWiJygNC35U39ywzJBAAc2lZCzUhUL3ag4sSasns4F+sdyAW1BR29Dz/Vn5OFNty8WYYKBnO88OMFy/ElVLUdxdM7sKuN4YR5d9lNFmQFSfwXV63kHImFue31a9zbIyKpobqILAoGBAIn4zSiz98puPVuPXue6tI19WViL3j9qfsCBNwW0YCFHSOjfQSdqw0BGJQBqrCnCbB9bsTwqkVSOBmAa5DWO8r9jvlkoHrQE5CPz9v13PRcl9Fl1Xcry2ziBCSF9XSkTUj9Ep6boR3rCKKv3UfH3mmTI4kKRqkXz7Vmgt3qK4stBAoGBANXjcNjaB0Wqa9Ee0RHx9WDfHKPR7d/4P7KUsUU85eoEjQUhambcUdCO8lL8433vFdrLxHRCw/MVAbm3v+VasQpyNhU90L7v+PWre44V6vnGVrMaZsl6HBT3gIbtCEkgev6grGTAGdzlzHib8ScQBsYb2K5nwum4uG3/SgGgUbMPAoGBAMrPGA4Yn1nhsAa+YDNXVTjwzBs9yb3DCo2rYlKOSgRwK2MLHV6XRKPm9trTpR6wly/Q3Lb3IyXmKK1J2ff09LgsMIQLkOtf7qZMc6SGJLhRzEYgsY4Ri0S4FfEkDrqcc2xpcVIRRwsGbN8r2IMesSTtodNJj+hoAk6VDIPUPGzW',
        'log' => [
            'file' => './static/paylog/alipay.log',
            'level' => 'info',
            'type' => 'single',
            'max_file' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            'verify'  =>  false
        ],
    ];

    public function index($order){
        $alipay = Pay::alipay($this->config)->scan($order);
        return $alipay;
    }

    public function notify()
    {
        $alipay = Pay::alipay($this->config);
        try{
            $data = $alipay->verify();
            if ($data && $data->trade_status){
                if ($data->trade_status == 'TRADE_SUCCESS' || $data->trade_status == 'TRADE_FINISHED'){
                    if ($data->out_trade_no && $data->trade_no){
                        (new Notify())->orderUpdateStatus(
                            $data->app_id,
                            $this->config['app_id'],
                            $data->out_trade_no,
                            $data->trade_no,
                            $data->total_amount*100,
                            $data->gmt_payment
                        );
                    }
                }
            }
            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }
        return $alipay->success()->send();
    }

    /** 查询订单
     * @param $result
     */
    public function find($orderNO){
        $pay = Pay::alipay($this->config);
        $result = $pay->find($orderNO);
        if ($result && $result->trade_status && $result->trade_status == 'TRADE_SUCCESS' && $result->code == '10000'){//返回查询结果集
            return $result;
        }
        return false;
    }
}