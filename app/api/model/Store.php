<?php
namespace app\api\model;

class Store extends BaseModel{

    protected $field = ['id','name','province','city','area','address','contacts','phone','poster','logo','level','longitude','latitude','license','bank','bankname','status','create_time'];

    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;


    /**
     * 分页查询
     */
    public static function getPageData($page,$size,$where=[]){
        $data = self::field('s.*,a.name city_name')
                ->alias('s')
                ->join('address a','a.id=s.city')
                ->where($where)
                ->order('s.id','asc')
                ->paginate($size,true,['page'=>$page]);
        $total = self::field('s.*,a.name')
                ->alias('s')
                ->join('address a','a.id=s.city')
                ->where($where)
                ->count('s.id');
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}