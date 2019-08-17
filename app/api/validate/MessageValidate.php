<?php
namespace app\api\validate;

class MessageValidate extends BaseValidate{

    protected $rule = [
        'owner'      => 'require|min:1|max:15',
        'title'      => 'require|min:1|max:30',
        'year'       => 'require',
        'store_id'   => 'require|isNotEmpty|number',
        'car_id'     => 'require|isNotEmpty|number',
        'main'       => 'require|min:1',
    ];

    protected $message = [
        'owner'      => '车主填写不正确',
        'title'      => '资讯标题填写不正确',
        'year'       => '提车日期填写不正确',
        'store_id'   => '门店必须选择',
        'car_id'     => '车辆id填写不正确',
        'image'      => '图片必须上传',
        'main'       => '内容正文填写不正确',
    ];
}