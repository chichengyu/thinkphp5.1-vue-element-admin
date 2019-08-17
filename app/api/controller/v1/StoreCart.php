<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use app\api\validate\StoreCartValidate;
use think\Request;
use app\api\model\StoreCart as StoreCartModel;

class StoreCart extends Base{

    /**
     * 添加购物车
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new StoreCartValidate())->goCheck();
            $data = input('post.');
            $data['store_id'] = $this->userInfo->store_id;
            $where = [
                'car_id' => $data['car_id'],
                'car_attr_id' => $data['car_attr_id'],
                'store_id' => $data['store_id'],
            ];
            try{
                $res = StoreCartModel::where($where)->find();
                if ($res){
                    $res = StoreCartModel::where('id',$res->id)->setInc('car_number',$data['car_number']);
                    if ($res !== false){
                        return $this->responseSuccess('添加成功');
                    }
                }
                $res = StoreCartModel::create($data);
                if ($res){
                    return $this->responseSuccess('添加成功');
                }
                return $this->responseError('添加失败');
            }catch (\Exception $e){
                halt($e->getMessage());
                return $this->responseError('添加失败');
            }
        }
    }

    /**
     * 购物车列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $where = [];
        $data = StoreCartModel::getPageData($page,$limit,$where);
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            return $this->responseSuccess('success',$newData);
        }
        return $this->responseError('error');
    }

    /**
     * 购物车删除
     */
    public function del(){
        $res = StoreCartModel::destroy(input('get.ids'));
        if ($res){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('删除失败');
    }
}