<?php
namespace app\api\validate;

class CarDetailsValidate extends BaseValidate{

    protected $rule = [
        'name'          => 'require|min:1|max:32',
        'brand_id'      => 'require|isIdInteger',
        'years'         => 'require|date',
        'cartype_id'    => 'require|isIdInteger',
        'bsx'           => 'require|number',
        'displacement'  => 'require|min:1|max:10',
        'engine'        => 'min:1|max:20',
        'country_id'    => 'require|isIdInteger',
        'level'         => 'require',
        'carstyle'      => 'require|min:1|max:10',
        'fuel'          => 'require|min:1|max:10',
        'series'        => 'require|min:1',
        'price_zdj'     => 'require|float',
        'price_ptj'     => 'float',
        'price_mdj'     => 'require|float',
        'price_jxs'     => 'require|float',
        'imagesId'      => 'require',
    ];

    protected $message = [
        'name.require'  => '名称填写不正确',
        'name.unique'   => '名称已存在',
        'brand_id'      => '品牌必须选择',
        'years'         => '生产年份必须选择',
        'cartype_id'    => '车型必须选择',
        'bsx'           => '变速箱必须选择',
        'displacement'  => '汽车排量填写不正确',
        'engine'        => '发动机填写不正确',
        'country_id'    => '国别必须选择',
        'level'         => '级别必须填写',
        'carstyle'      => '车身形式填写不正确',
        'fuel'          => '燃油形式填写不正确',
        'series'        => '汽車系列填写不正确',
        'price_zdj'     => '指导价填写不正确',
        'price_ptj'     => '特价填写不正确',
        'price_mdj'     => '门店价填写不正确',
        'price_jxs'     => '经销商价填写不正确',
        'imagesId'      => '车辆图片必须上传',
    ];

}