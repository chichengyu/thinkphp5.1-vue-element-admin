<?php
namespace app\api\model;


class Administrators extends BaseModel{

    protected $field = ['id','tel','password','name','group_id','status','is_del','create_time'];


    /**
     * 分页查询
     */
    public static function pageAdminUser($page,$size,$where=[]){
        $data = self::field('au.id,au.name,au.address_id,au.store_name,au.group_id,au.tel,au.status,au.create_time,ag.id gid,ag.title')
                ->alias('au')
                ->join('auth_group ag','ag.id=au.group_id')
                ->where($where)
                ->paginate($size,true,['page'=>$page]);
        $total = self::field('au.id,au.name,au.group_id,au.tel,au.status,au.create_time,ag.id gid,ag.title')
                ->alias('au')
                ->join('auth_group ag','ag.id=au.group_id')
                ->where($where)
                ->count('au.id');
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}