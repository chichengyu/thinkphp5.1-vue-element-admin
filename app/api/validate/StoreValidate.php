<?php
namespace app\api\validate;

class StoreValidate extends BaseValidate{

    protected $rule = [
        'name'     => 'require|unique:store|min:1|max:16',
        'province' => 'require|number',
        'city'     => 'require|number',
        'area'     => 'require|number',
        'address'  => 'require|min:1|max:32',
        'contacts' => 'require|min:1|max:10',
        'phone'    => 'require|isMobile',
        'level'    => 'require|between:0,2',
        'license'  => 'require|min:1|max:255',
        'bankname' => 'require|min:1|max:32',
        'bank'     => 'require|min:10|max:21',
    ];

    protected $message = [
        'name'     => '名称填写不正确',
        'name.store' => '名称已存在',
        'province' => '省份填写不正确',
        'city'     => '市区填写不正确',
        'area'     => '区/县填写不正确',
        'address'  => '详细地址填写不正确',
        'contacts' => '联系人填写不正确',
        'phone'    => '手机填写不正确',
        'level'    => '级别必须选择',
        'license'  => '营业执照必须上传',
        'bankname' => '开户银行名称必须填写',
        'bank'     => '卡号填写不正确',
    ];
}