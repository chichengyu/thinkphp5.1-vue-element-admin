<?php
namespace sms;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AlySmsApi{

    public static function smsSend($tel,$params,$tepCode='SMS_167526275'){
        // 设置全局客户端
        AlibabaCloud::accessKeyClient('LTAIs49pgx8XWXg1', '84KRAsoy5VkPpEYLhmU8gBYgyztNaB')
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $tel,
                        'SignName' => "车亿百",
                        'TemplateCode' => $tepCode,
                        'TemplateParam' => json_encode($params)
                    ],
                ])
                ->request();
            $res = $result->toArray();

            if(is_array($res) && array_key_exists('Message',$res) && $res['Message'] == 'OK'){
                return true;
            }
            return false;
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }



    }
}