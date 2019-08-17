<?php
namespace app\api\validate;

use app\lib\exception\OrderException;

class OrderValidate extends BaseValidate{

    // products = [ [ 车主id, 数量, 车主信息 ] ....];
    protected $rule = [
        'products'  => 'require|checkProducts',
    ];


    protected function checkProducts($values){
        if (empty($values)){
            throw new OrderException(['msg' => '商品列表不能为空']);
        }
        if (!is_array($values)){
            throw new OrderException(['msg' => '商品参数错误']);
        }
        foreach ($values as $v){
            $this->checkProduct($v);
        }
        return true;
    }

    // 验证子级单个商品
    protected function checkProduct($value){
        // new父级的验证对象是为了能调用父级的 isIdInteger 验证方法
        $validater = new BaseValidate(array(
            'car_id' => 'require|isIdInteger',
            'count' => 'require|isIdInteger'
        ));
        $res = $validater->check($value);
        if (!$res) {
            throw new OrderException(array('msg'=>'商品参数不正确'));
        }
        $owner = new StoreOwnerValidate();
        foreach ($value['owner'] as $v){
            $res = $owner->check($v);
            if (!$res) {
                throw new OrderException(array('msg'=>$owner->getError()));
            }
        }
    }
}