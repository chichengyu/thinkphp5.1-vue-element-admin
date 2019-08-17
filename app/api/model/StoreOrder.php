<?php
namespace app\api\model;

class StoreOrder extends BaseModel{

    protected $field = ['id','store_id','gifts','order_no','order_status','after_status','count','total_price','order_amount_total','logistics_fee','pay_channel','pay_time','delivery_time','order_settlement_status','order_settlement_time','pay_type','is_del','transaction_id','create_time','update_time','delete_time'];

    public function getcreateTimeAttr($val){
        return date('Y-m-d H:i:s',$val);
    }



    public function storeOwner(){
        return $this->hasMany(StoreOwner::class,'store_order_id','id');
    }

    /**
     * 分页查询
     */
    public static function getPageData($page,$size,$where=[]){
        $data = self::with(['storeOwner'=>['carDetails'=> function($query){
                $query->visible(['id','name','classify','thumbnail','price_zdj','price_mdj','price_jxs']);
            },'carNumber']])
            ->where($where)
//            ->order('id desc')
            ->paginate($size,true,['page'=>$page]);

//        $data = self::field('o.*,s.name store_name,cd.name car_name')
//            ->alias('o')
//            ->join('store s','s.id=o.store_id')
//            ->join('store_owner so','so.store_order_id=o.id')
//            ->join('car_number cn','cn.id=so.car_number_id')
//            ->join('car_details cd','cd.id=so.car_id')
//            ->where($where)
//            ->paginate($size,true,['page'=>$page]);
        $total = self::where($where)->count('id');

        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}