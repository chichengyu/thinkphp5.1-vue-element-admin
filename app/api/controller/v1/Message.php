<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use app\api\validate\MessageValidate;
use think\Request;
use app\api\model\Message as MessageModel;
use app\api\model\Store as StoreModel;
use app\api\model\CarDetails as CarDetailsModel;

class Message extends Base{

    /**
     * 添加资讯
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new MessageValidate)->goCheck();
            $car_id = input('post.car_id');
            $res = CarDetailsModel::where('id',$car_id)->find();
            if (!$res){
                return $this->responseError('无效的车辆ID');
            }
            $message = new MessageModel;
            $res = $message->allowField(true)->save(input('post.'));
            if ($res){
                return $this->responseSuccess('添加成功');
            }
            return $this->responseError('添加失败'.$message->getError());
        }
    }

    /**
     * 资讯列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $keywords = input('get.keywords');
        $status = input('get.status');
        $where = [];
        if (isset($keywords)){
            $where[] = ['m.owner|m.title','like',["%$keywords%"]];
        }
        if (isset($status) && in_array($status,[0,1])){
            $where[] = ['m.status','=',$status];
        }
        $data = MessageModel::getPageData($page,$limit,$where);
        $store = StoreModel::field('id,name')->where('status',1)->all();
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            $newData['stores'] = $store;
            return $this->responseSuccess('success',$newData);
        }
        return $this->responseError('error');
    }

    /**
     * 资讯编辑
     */
    public function edit(Request $request,$id){
        if($request->isPost()){
            $status = input('post.status');
            if (isset($status) && in_array($status,[0,1])){
                $res = MessageModel::where('id',$id)->setField('status',$status);
                if ($res !== false){
                    return $this->responseSuccess('修改成功');
                }
                return $this->responseError('修改失败');
            }
            (new IDMustValidate)->goCheck();
            (new MessageValidate)->goCheck();
            $car_id = input('post.car_id');
            $res = CarDetailsModel::where('id',$car_id)->find();
            if (!$res){
                return $this->responseError('无效的车辆ID');
            }
            $res = MessageModel::where('id',$id)->find();
            if ($res){
                if ((input('post.image') !== $res->image) && !$this->delImage($res->image)){
                    return $this->responseError('修改失败');
                }
                $message = new MessageModel;
                $res = $message->allowField(true)->save(input('post.'),['id'=>$id]);
                if ($res){
                    return $this->responseSuccess('修改成功');
                }
                return $this->responseError('修改失败'.$message->getError());
            }
            return $this->responseError('数据不存在');
        }
    }

    /**
     * 资讯删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = MessageModel::get($id);
        if ($res->id){
            if ($res->image && !$this->delImage($res->image)){
                return $this->responseError('删除失败');
            }
            if ($res->delete()){
                return $this->responseSuccess('删除成功');
            }
            return $this->responseError('删除失败');
        }
        return $this->responseError('数据不存在');
    }

    /**
     * 资讯Logo上传
     */
    public function upload(Request $request)
    {
        if ($request->isPost()){
            $file = $request->file('file');
            $res = $this->imageUpload($file,'message');
            return json($res);
        }
    }

}