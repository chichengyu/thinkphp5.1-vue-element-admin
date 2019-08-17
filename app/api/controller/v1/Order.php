<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use app\api\validate\OrderValidate;
use think\Request;
use app\api\model\StoreOrder as StoreOrderModel;
use app\api\service\Order as OrderService;


class Order extends Base{

    /**
     * 下单
     */
    public function placeOrder(Request $request){
        if ($request->isPost()){
            (new OrderValidate())->goCheck();
            $ids = input('post.store_cart_ids');
            $products = input('post.products/a');
            $payType = input('post.pay_type/d');
            $order = (new OrderService)->place($this->userInfo->store_id,$payType,$products);
            if (!empty($order)){
                $res = model('StoreCart')->where('id','in',$ids)->delete();
                if ($res !== false){
                    return $this->responseSuccess('下单成功',$order);
                }
            }
            return $this->responseSuccess('下单失败');
        }
    }

    /**
     * 订单列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $keywords = input('get.keywords');
        $status = input('get.status');
        $date = input('get.date');
        $where = [
            ['store_id','=',$this->userInfo->store_id],
            ['is_del','<>',1],
        ];
        if (isset($keywords)){
            $where[] = ['order_no','like',["%$keywords%"]];
        }
        if (isset($status)){
            $where[] = ['order_status','=',$status];
        }
        if (isset($date)){
            $date = explode(',',$date);
            $where[] = ['create_time','between time',[$date[0],$date[1]]];
        }
        $data = StoreOrderModel::getPageData($page,$limit,$where);
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            return $this->responseSuccess('success',$newData);
        }
        return $this->responseError('error');
    }

    /**
     * 订单删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = StoreOrderModel::get($id);
        $res->is_del = 1;
        if ($res->save()){
            return $this->responseSuccess('取消成功');
        }
        return $this->responseError('删除失败');
    }
}