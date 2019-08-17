<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use app\api\validate\StoreOwnerValidate;
use think\Request;
use app\api\model\StoreOwner as StoreOwnerModel;

class StoreOwner extends Base{

    /**
     * 车主添加
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new StoreOwnerValidate)->goCheck();
            $data = input('post.');
            $storeOwnerM = new StoreOwnerModel;
            try{
                if ($storeOwnerM->allowField(true)->save($data)){
                    return $this->responseSuccess('添加成功');
                }
                return $this->responseError('添加失败！'.$storeOwnerM->getError());
            }catch (\Exception $e){
                return $this->responseError('添加失败！');
            }
        }
    }

    /** 身份证上传
     * @param Request $request
     */
    public function updaloadIdCard(Request $request){
        if ($request->isPost()){
            $res = $this->imageUpload($request->file('file'),'owner');
            return json($res);
        }
    }

    /** 更新
     * @param $id
     */
    public function edit(Request $request,$id){
        if ($request->isPost()){
            (new IDMustValidate())->goCheck();
            $data = input('post.');
            $reason = input('post.reason/s');
            if (isset($reason)){
                $res = StoreOwnerModel::where('id',$id)->setField('reason',$reason);
                if ($res !== false){
                    return $this->responseSuccess('退款申请提交成功！');
                }
                return $this->responseError('退款申请提交失败！');
            }
            if (isset($data['takeover']) && isset($data['takeover_tel'])){
                $storeOwnerM = StoreOwnerModel::get($id);
                $storeOwnerM->order_status = 2;
                $storeOwnerM->takeover = $data['takeover'];
                $storeOwnerM->takeover_tel = $data['takeover_tel'];
                if ($storeOwnerM->save($data)){
                    return $this->responseSuccess('确认收货成功！');
                }
                return $this->responseError('确认收货失败！');
            }
            return $this->responseError('提交失败！');
        }
    }
}