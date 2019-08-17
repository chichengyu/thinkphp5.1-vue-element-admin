<?php
namespace app\api\validate;

class AuthRuleValidate extends BaseValidate{

    protected $rule = [
        'title'  => 'require|min:1|max:16',
//        'name'   => 'require|min:1|max:32',
    ];

    protected $message = [
        'title'  => '规则名称填写不正确',
//        'name'   => '规则填写不正确',
    ];
}