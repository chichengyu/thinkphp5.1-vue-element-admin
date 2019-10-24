<?php
namespace app\api\validate;

class AuthGroupsValidate extends BaseValidate{

    protected $rule = [
        'title'      => 'require|unique:auth_group|min:1|max:16',
    ];

    protected $message = [
        'title.unique' => '名称已存在',
        'title'        => '名称填写不正确',
    ];
}