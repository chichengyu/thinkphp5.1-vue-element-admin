<?php
namespace app\api\model;

use think\Model;
use think\Db;
use think\facade\Log;


class BaseModel extends Model{

    protected function initialize(){
        if (config('app_debug')) {
            Db::listen(function ($sql, $time, $explain) {
                // 记录SQL
                Log::write($sql . ' [' . $time . 's]','sql');
            });
        }
    }

    /**
     * 分页查询
     */
    public static function pageData($page,$size,$where=[],$order='asc'){
        $data = self::where($where)->order('id',$order)->paginate($size,true,['page'=>$page]);
        $total = self::where($where)->count('id');
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}