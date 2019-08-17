<?php
namespace app\api\controller\v1;

use app\api\validate\AuthRuleValidate;
use app\api\validate\IDMustValidate;
use think\Request;
use app\api\model\StoreAuthRule as AuthRuleModel;

class StoreAuthRule extends Base{

    /**
     * 添加权限
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new AuthRuleValidate)->goCheck();
            $data = input('post.');
            $res = AuthRuleModel::where(['name' => $data['name'],'title' => $data['title']])->find();
            if ($res){
                return $this->responseError('规则已存在');
            }
            $numberuser = new AuthRuleModel;
            $res = $numberuser->allowField(true)->save($data);
            if ($res){
                return $this->responseSuccess('添加成功');
            }
            return $this->responseError('添加失败'.$numberuser->getError());
        }
    }

    /**
     * 权限列表
     */
    public function lst(){
        $data = AuthRuleModel::getTreeList();
        $parentData = AuthRuleModel::select();
        if ($data){
            $data['parent'] = AuthRuleModel::_getTree($parentData);
            return $this->responseSuccess('success',$data);
        }
        return $this->responseError('error');
    }

    /**
     * 权限编辑
     */
    public function edit(Request $request,$id){
        if($request->isPost()){
            (new IDMustValidate)->goCheck();
            (new AuthRuleValidate)->goCheck();
            $res = AuthRuleModel::get($id);
            if ($res && $res->save(input('post.'))){
                return $this->responseSuccess('修改成功');
            }
            return $this->responseError('修改成功修改失败');
        }
    }

    /**
     * 权限删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = AuthRuleModel::get($id);
        if ($res && $res->delete()){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('数据不存在');
    }
}