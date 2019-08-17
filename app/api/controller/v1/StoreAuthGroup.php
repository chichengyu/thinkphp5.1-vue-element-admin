<?php
namespace app\api\controller\v1;

use app\api\validate\AuthGroupsValidate;
use app\api\validate\IDMustValidate;
use think\Request;
use app\api\model\StoreAuthGroup as AuthGroupsModel;
use app\api\model\StoreAuthRule as AuthRuleModel;

class StoreAuthGroup extends Base{

    /**
     * 分配权限
     */
    public function setRules(Request $request,$id){
        if ($request->isPost()){
            $ids = $request->post('rules');
            if (!$ids){
                return $this->responseError('参数有误');
            }
            $authGroupsM = AuthGroupsModel::get($id);
            $authGroupsM->rules = $ids;
            $res = $authGroupsM->save();
            if ($res){
                return $this->responseSuccess('分配权限成功');
            }
            if ($res == 0){
                return $this->responseSuccess('无更新');
            }
            return $this->responseError('分配权限失败');
        }
    }

    /**
     * 添加用户组
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new AuthGroupsValidate)->goCheck();
            $data = input('post.');
            $numberuser = new AuthGroupsModel;
            $res = $numberuser->save($data);
            if ($res){
                return $this->responseSuccess('添加成功');
            }
            return $this->responseError('添加失败'.$numberuser->getError());
        }
    }

    /**
     * 用户组列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $data = AuthGroupsModel::pageData($page,$limit);
        $rules = AuthRuleModel::getChild();
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            $newData['rules'] = $rules;
            return $this->responseSuccess('success',$newData);
        }
        return $this->responseError('error');
    }

    /**
     * 用户组编辑
     */
    public function edit(Request $request,$id){
        if($request->isPost()){
            (new IDMustValidate)->goCheck();
            (new AuthGroupsValidate)->goCheck();
            $res = AuthGroupsModel::get($id);
            if ($res && $res->save(input('post.'))){
                return $this->responseSuccess('修改成功');
            }
            return $this->responseError('修改成功修改失败');
        }
    }

    /**
     * 用户组删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = AuthGroupsModel::get($id);
        if ($res && $res->delete()){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('数据不存在');
    }
}