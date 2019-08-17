<?php
namespace app\api\validate;

class StorePersonnelValidate extends BaseValidate{

    protected $rule = [
        'name' => 'require|min:1|max:10',
        'tel'      => 'require|isMobile|unique:storePersonnel',
        'password' => 'require|verfyPassword|confirm:password_comfirm',
        'position' => 'require|min:1|max:32',
    ];

    protected $message = [
        'name'       => '名称填写不正确',
        'tel'        => '手机填写不正确',
        'tel.unique' => '手机号码已存在',
        'password'   => '密码填写不正确',
        'password.confirm' => '两次密码输入不一致',
        'position'   => '职位填写不正确',
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