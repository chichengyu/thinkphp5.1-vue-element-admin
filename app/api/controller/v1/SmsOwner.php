<?php
namespace app\api\controller\v1;

use sms\AlySmsApi;

class SmsOwner extends Base
{
    public function informSms()
    {
        $tel = input('post.tel');
        $params = [
            'owner' => input('post.owner'),
            'name'  =>  input('post.name'),
            'level' =>  input('post.level'),
            'store'  =>  input('post.store'),
        ];
        $tepCode = 'SMS_170347164';
        $res = AlySmsApi::smsSend($tel,$params,$tepCode);
        if($res==true){
            return $this->responseSuccess('发送成功');
        }
        return $this->responseError('发送失败');
    }
}