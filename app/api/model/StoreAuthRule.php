<?php
namespace app\api\model;

class StoreAuthRule extends BaseModel {

    protected $field = ['id','name','title','type','status','condition','pid'];

    /*================================ 权限列表使用 ===============================*/
    /** 用于权限列表显示的结构数据
     * @param array $where
     * @return array
     * @throws
     */
    public static function getTreeList($where=[]){
        $data = self::where($where)->all();
        $data = self::_getTree($data);
        $total = count($data);
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
    /**
     * 无限极分类（权限列表）
     */
    public static function _getTree($data,$parent_id=0,$level=0){
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $parent_id){
                $v['level'] = $level;
                unset($data[$k]);
                $arr[] = $v;
                $arr = array_merge($arr,self::_getTree($data,$v['id'],$level+1));
            }
        }
        return $arr;
    }

    /*================================ 用户组分配权限使用 ===============================*/
    /** 用于分配权限时显示的结构数据
     * @return array
     * @throws
     */
    public static function getChild(){
        $data = self::where('status',1)->all();
        $data = self::_Child($data);
        return $data;
    }

    /** 获取分配权限的 tree 结构数据
     * @param $data
     * @param int $parent_id
     * @param string $child
     * @return array
     */
    protected static function _Child($data, $parent_id=0, $child='_child'){
        $arr = [];
        foreach ($data as $k=>$v){
            if ($v['pid'] == $parent_id){
                unset($data[$k]);
                if (!empty($data)){
                    $child = self::_Child($data,$v['id'],$child);
                    if (!empty($child)){
                        $v['child'] = $child;
                    }
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }
}