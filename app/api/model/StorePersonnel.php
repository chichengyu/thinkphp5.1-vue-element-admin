<?php
namespace app\api\model;

class StorePersonnel extends BaseModel{

    protected $field = ['id','name','tel','password','position','store_id','group_id','status','create_time'];

    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;

    /**
     * 分页查询
     */
    public static function pageData($page,$size,$where=[],$order='asc'){
        $data = self::field('sp.*,sag.title group_name')
            ->alias('sp')
            ->join('store_auth_group sag','sag.id=sp.group_id')
            ->where($where)
            ->order('sp.id',$order)
            ->paginate($size,true,['page'=>$page]);
        $total = self::alias('sp')
            ->join('store_auth_group sag','sag.id=sp.group_id')
            ->where($where)
            ->order('sp.id',$order)
            ->count('sp.id');
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}
