<?php
namespace app\api\controller\v1;

use app\api\validate\AuthRuleValidate;
use app\api\validate\IDMustValidate;
use think\Request;
use app\api\model\AuthRule as AuthRuleModel;

class AuthRule extends Base{

    /**
     * 添加权限
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new AuthRuleValidate)->goCheck();
            $data = input('post.');
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
        $data = AuthRuleModel::getChild();
        $parentData = AuthRuleModel::select();
        if (isset($data)){
            $newData['data'] = $data;
            $newData['parent'] = AuthRuleModel::_getTree($parentData);
            return $this->responseSuccess('success',$newData);
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
                return $this->responseSuccess('编辑成功');
            }
            return $this->responseError('编辑失败');
        }
    }

    /**
     * 权限删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        AuthRuleModel::where('pid',$id)->delete();
        $res = AuthRuleModel::get($id);
        if ($res && $res->delete()){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('数据不存在');
    }
}