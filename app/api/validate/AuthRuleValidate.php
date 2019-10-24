<?php
namespace app\api\validate;

class AuthRuleValidate extends BaseValidate{

    protected $rule = [
        'title'  => 'require|min:1|max:16|unique:auth_rule',
        'name'   => 'unique:auth_rule',
    ];

    protected $message = [
        'title'  => '名称填写不正确',
        'title.unique'  => '名称已存在',
        'name'   => '规则已存在',
    ];
}