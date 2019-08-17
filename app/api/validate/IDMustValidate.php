<?php
namespace app\api\validate;

class IDMustValidate extends BaseValidate{

    protected $rule = array(
        'id' => 'require|isIdInteger'
    );
    protected $message = array(
        'id' => 'id必须是正整数'
    );
}