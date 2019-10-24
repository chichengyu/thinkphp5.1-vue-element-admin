<?php
namespace app\api\controller\v1;

use think\Request;
use think\Db;

class Address extends Base{

    /** 三级联动数据
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function getAddress(Request $request){
        if ($request->isGet()){
            $address = Db::name('address')->select();
            if (!$address->isEmpty()){
                return $this->responseSuccess('success',$address);
            }
            return $this->responseError('error');
        }
    }

}