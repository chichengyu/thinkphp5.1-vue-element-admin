<?php
namespace app\api\validate;

class StoreOwnerValidate extends BaseValidate{

    protected $rule = [
        'owner_name'        => 'require|min:1|max:32',
        'owner_tel'         => 'require|isMobile',
        'owner_identity_f'  => 'require|max:255',
        'owner_identity_r'  => 'require|max:255',
    ];

    protected $message = [
        'owner_name'        => '车主名称填写不正确',
        'owner_tel'         => '车主联系方式填写不正确',
        'owner_identity_f'  => '车主身份证正面必须上传',
        'owner_identity_r'  => '车主身份证反面必须上传',
    ];

}