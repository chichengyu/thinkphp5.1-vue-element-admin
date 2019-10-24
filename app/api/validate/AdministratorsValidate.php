<?php
namespace app\api\validate;

class AdministratorsValidate extends BaseValidate{

    protected $rule = [
        'tel'      => 'require|isMobile|unique:administrators|length:11',
        'name'     => 'require|min:1|max:10',
        'group_id' => 'require|number',
        'address_id' => 'require',
        'name'     => 'require|min:1|max:10',
    ];

    protected $message = [
        'tel'        => '手机填写不正确',
        'tel.unique' => '手机号已存在',
        'name'       => '姓名填写不正确',
        'group_id'     => '用户组不正确',
        'address_id'   => '地址不正确',
    ];

    protected function verfyPassword($value,$rule='',$data='',$field=''){
        $reg = "/^[a-zA-Z0-9_-]{8,16}$/";
        $result = preg_match($reg,$value);
        if ($result) {
            return true;
        }
        return false;
    }
}