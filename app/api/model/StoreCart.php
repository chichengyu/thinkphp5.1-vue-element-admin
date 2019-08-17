<?php
namespace app\api\model;

class StoreCart extends BaseModel{

    protected $field = ['id','car_id','car_attr_id','car_number','store_id','create_time'];
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    /**
     * 分页查询
     */
    public static function getPageData($page,$size,$where=[]){
        $data = self::field('sc.*,cd.name car_name,cd.classify,cd.thumbnail thumbnail,cd.price_zdj,cd.price_jxs,cd.price_mdj,cn.car_color')
            ->alias('sc')
            ->join('car_details cd','cd.id=sc.car_id')
            ->join('car_number cn','cn.id=sc.car_attr_id')
            ->where($where)
            ->order('sc.id desc')
            ->paginate($size,true,['page'=>$page]);
        $total = self::field('sc.id,cd.name car_name,cd.classify,cn.car_color')
            ->alias('sc')
            ->join('car_details cd','cd.id=sc.car_id')
            ->join('car_number cn','cn.id=sc.car_attr_id')
            ->where($where)
            ->count('sc.id');

        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}