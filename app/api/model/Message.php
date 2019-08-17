<?php
namespace app\api\model;


class Message extends BaseModel{

    protected $field = ['id','owner','title','year','store_id','car_id','image','main','status','create_time'];

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function getMainAttr($value,$data){
        return htmlspecialchars_decode($value);
    }


    /**
     * 分页查询
     */
    public static function getPageData($page,$size,$where=[]){
        $data = self::field('m.*,s.name store_name,cd.name car_name')
                    ->alias('m')
                    ->join('store s','s.id=m.store_id')
                    ->join('car_details cd','cd.id=m.car_id')
                    ->where($where)
                    ->paginate($size,true,['page'=>$page]);
        $total = self::field('m.*,s.name store_name,cd.name car_name')
            ->alias('m')
            ->join('store s','s.id=m.store_id')
            ->join('car_details cd','cd.id=m.car_id')
            ->where($where)
            ->count('m.id');
//        $total = self::where($where)->count('id');
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}