<?php
namespace app\admin\controller\v1;


use app\common\model\StoreOrder as StoreOrderModel;
use app\common\model\StoreLogistics as StoreLogisticsModel;
use think\Request;
use app\admin\service\Pay as PayService;

class Pay extends Base
{
    /** 退款
     * @param Request $request
     * @param $id
     */
    public function refund(Request $request,$id){
        if ($request->isGet()){
            $result = (new PayService())->index($id);
            if ($result){
                return $this->responseSuccess('退款成功！',1);
            }
            return $this->responseError('退款失败！');
        }
    }
}