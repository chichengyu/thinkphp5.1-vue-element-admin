<?php
namespace app\api\validate;

class StoreCartValidate extends BaseValidate{

    protected $rule = [
        'car_id'        => 'require|isNotEmpty|isIdInteger',
        'car_attr_id'   => 'require|isNotEmpty|isIdInteger',
        'car_number'    => 'require|isNotEmpty|isIdInteger',
        'owner_id'      => 'isNotEmpty|isIdInteger'
    ];

    protected $message = [
        'car_id'        => '车辆id不正确',
        'car_attr_id'   => '车辆属性id不正确',
        'car_number'    => '车辆数量不正确',
        'owner_id'      => '车主id不正确'
    ];
}