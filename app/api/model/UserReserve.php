<?php
namespace app\api\model;

class UserReserve extends BaseModel{

    protected $field = ['id','username','storeid','carid','tel','status','docker','dockertel','source','remark','appointment_time','update_time'];

    protected $autoWriteTimestamp = true;
    protected $createTime = false;

    /**
     * 分页查询
     */
    public static function getPageData($page,$size,$where=[]){
        $data = self::field('ur.*,s.name,cd.name cart_name,cd.brand_id,cd.cartype_id type_id,sp.name personnel_name,sp.tel personnel_tel')
                ->alias('ur')
                ->join('store s','s.id=ur.storeid','LEFT')
                ->join('car_details cd','cd.id=ur.carid','LEFT')
                ->join('store_personnel sp','sp.id=ur.personnel_id','LEFT')
                ->where($where)
                ->paginate($size,true,['page'=>$page]);
        $total = self::field('ur.*,s.name,cd.name cart_name,cd.brand_id,cd.cartype_id type_id')
                ->alias('ur')
                ->join('store s','s.id=ur.storeid','LEFT')
                ->join('car_details cd','cd.id=ur.carid','LEFT')
                ->join('store_personnel sp','sp.id=ur.personnel_id','LEFT')
                ->where($where)
                ->count();
        return [
            'data'  => $data,
            'total' => $total
        ];
    }

}