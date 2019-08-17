<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use think\Request;
use app\api\model\UserReserve as UserReserveModel;

class UserReserve extends Base{

    /**
     * @param Request $request
     * @param $brandId  品牌id
     * @param $typeId   类型
     */
    public function getByBrandOrTypeCar(Request $request){
        if ($request->isGet()){
            $brandId = input('get.brand_id');
            $type = input('get.type_id');
            $where = [];
            !empty($brandId) && $where[] = ['brand_id','=',$brandId];
            !empty($type) && $where[] = ['cartype_id','=',$type];
            $data = model('CarDetails')::field('id,name')->where($where)->select();
            if ($data && !$data->isEmpty()){
                return $this->responseSuccess('success',$data);
            }
            return $this->responseError('error');
        }
    }

    /**
     * 咨询添加
     */
    public function add(Request $request){
        if($request->isPost()){
            $data = input('post.');
            $personnelId = 0;
            $source= '门店负责人';
            if ($this->userInfo->status != 1){
                $personnelId = $this->userInfo->id;
                $source= '门店员工';
            }
            $data = [
                'username' => $data['username'],
                'storeid' => $this->userInfo->store_id,
                'personnel_id' => $personnelId,
                'carid' => $data['carid'],
                'tel' => $data['tel'],
                'source' => $source,
                'remark' => $data['remark'],
                'appointment_time' => date('Y-m-d H:i:s'),
                'update_time' => 0
            ];
            $userReserveM = new UserReserveModel;
            if ($userReserveM->allowField(true)->save($data)){
                return $this->responseSuccess('修改成功');
            }
            return $this->responseError('修改失败');
        }
    }

    /**
     * 咨询列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $keywords = input('get.keywords');
        $status = input('get.status');
        $date = input('get.date');
        $where = [];
        if ($this->userInfo->id != 1){
            $where[] = ['storeid','=',$this->userInfo->store_id];
        }
        if ($this->userInfo->status != 1){
            $where[] = ['personnel_id','=',$this->userInfo->id];
        }
        if (isset($keywords)){
            $where[] = ['ur.tel|ur.username|s.name|cd.name|sp.name','like',["%$keywords%"]];
        }
        if (isset($status) && in_array($status,[0,1])){
            $where[] = ['ur.status','=',$status];
        }
        if (isset($date)){
            $date = explode(',',$date);
            $where[] = ['ur.appointment_time','between time',[$date[0],$date[1]]];
        }
        $data = UserReserveModel::getPageData($page,$limit,$where);
        $catBrands = model('CarBrand')::field('id,name')->all();
        $carType = model('CarType')::field('id,name')->all();
        $personnel = model('StorePersonnel')::field('id,name')->where([
                        ['store_id','=',$this->userInfo->store_id],
                        ['status','<>',1]
                    ])
                    ->all();
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            $newData['carBrands'] = $catBrands;
            $newData['types'] = $carType;
            $newData['personnel'] = $personnel;
            return $this->responseSuccess('success',$newData);
        }
        return $this->responseError('error');
    }

    /**
     * 咨询转发
     */
    public function relay(Request $request,$id){
        if($request->isPost()){
            (new IDMustValidate)->goCheck();
            $personnelId = input('post.personnelId');
//            $data['source'] = '门店转发';
            $res = UserReserveModel::where('id',$id)->setField('personnel_id',$personnelId);
            if ($res !== false){
                return $this->responseSuccess('转发成功');
            }
            return $this->responseError('转发失败');
        }
    }

    /**
     * 咨询编辑
     */
    public function edit(Request $request,$id){
        if($request->isPost()){
            (new IDMustValidate)->goCheck();
            $data = input('post.');
            ($data['status']==1) && ($data['update_time'] = time());
            $userReserveM = UserReserveModel::where('id',$id)->find();
            if ($userReserveM->allowField(true)->save($data)){
                return $this->responseSuccess('修改成功');
            }
            return $this->responseError('修改失败');
        }
    }

    /**
     * 咨询删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = UserReserveModel::where('id',$id)->delete();
        if ($res){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('删除失败');
    }

}