<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustValidate;
use app\api\validate\StorePersonnelValidate;
use think\Db;
use think\Request;
use app\api\model\StorePersonnel as StorePersonnelModel;
use app\api\model\StoreAuthGroupAccess as StoreAuthGroupAccessModel;
use app\api\model\StoreAuthGroup as StoreAuthGroupModel;

class StorePersonnel extends Base{

    /** 获取个人资料
     * @return \think\response\Json
     */
    public function getUserinfo(){
        $user = StorePersonnelModel::where('id',$this->userInfo->id)->find()->hidden(['password']);
        if ($user->id && ($user->group_id != 0)){
            $group = StoreAuthGroupModel::field('title')->where('id',$user->group_id)->find();
            if ($group){
                $user->group_title = $group->title;
            }
        }
        if ($user){
            return $this->responseSuccess('success',$user);
        }
        return $this->responseError('error');
    }

    /** 修改个人资料
     * @param Request $request
     */
    public function myEdit(Request $request){
        if ($request->isPost()){
            $oldPassword = input('post.oldPassword');
            $password = input('post.password');
            $name = input('post.name');
            try{
                if (isset($name)){
                    $res = StorePersonnelModel::where('id',$this->userInfo->id)->setField('name',$name);
                    if ($res !== false){
                        return $this->responseSuccess('修改成功！');
                    }
                    return $this->responseError('修改失败！');
                }else{
                    if (!isset($oldPassword)){
                        return $this->responseError('旧密码必须输入！');
                    }
                    $user = StorePersonnelModel::where('id',$this->userInfo->id)->find();
                    if (!password_verify(md5($oldPassword),$user->password)){
                        return $this->responseError('旧密码输入不正确！');
                    }
                    $password = password_hash(md5($password),PASSWORD_DEFAULT);
                    $res = StorePersonnelModel::where('id',$this->userInfo->id)->setField('password',$password);
                    if ($res !== false){
                        return $this->responseSuccess('修改成功！');
                    }
                    return $this->responseError('修改失败！');
                }
            }catch (\Exception $e){
                return $this->responseError('修改失败！');
            }
        }
    }

    /**
     * 添加员工
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new StorePersonnelValidate)->goCheck();
            $store_id = $this->userInfo->store_id;
            if (!$store_id){
                return $this->responseError('门店负责人才有权限添加');
            }
            $data = input('post.');
            $data['store_id'] = $store_id;
            $data['password'] = password_hash(md5($data['password']),PASSWORD_DEFAULT);
            $storePersonnelM = new StorePersonnelModel;
            $storeAuthGroupAccessM = new StoreAuthGroupAccessModel;
            Db::startTrans();
            try{
                $res = $storePersonnelM->save($data);
                if ($res){
                    $storeAuthGroupAccessM->uid = $storePersonnelM->id;
                    $storeAuthGroupAccessM->group_id = $data['group_id'];
                    if ($storeAuthGroupAccessM->save()){
                        Db::commit();
                        return $this->responseSuccess('添加成功');
                    }
                }
                return $this->responseError('添加失败');
            }catch (\Exception $e){
                Db::rollback();
                return $this->responseError('添加失败');
            }

        }
    }

    /**
     * 员工列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $tel = input('get.tel');
        $username = input('get.username');
        $where = [
            ['sp.id','<>',1]
        ];
        if ($this->userInfo->id != 1){
            $where[] = ['sp.status','=',0];
            $where[] = ['sp.store_id','=',$this->userInfo->store_id];
        }
        (isset($tel)) && ($where[] = ['sp.tel','like',["%$tel%"]]);
        (isset($username)) && ($where[] = ['sp.name','like',["%$username%"]]);
        $data = StorePersonnelModel::pageData($page,$limit,$where);
        $groups = Db::name('store_auth_group')->field('id,title')->select();
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            $newData['groups'] = $groups;
            return $this->responseSuccess('success',$newData);
        }
        return $this->responseError('error');
    }

    /**
     * 编辑员工
     */
    public function edit(Request $request,$id){
        if($request->isPost()){
            (new IDMustValidate)->goCheck();
            // 重置密码
            $ressetPasswd = input('post.reset');
            if (isset($ressetPasswd)){
                $res = StorePersonnelModel::where('id',$id)->setField('password',password_hash(md5('123456789'),PASSWORD_DEFAULT));
                if ($res !== false){
                    return $this->responseSuccess('重置密码成功！');
                }
                return $this->responseError('重置密码失败！');
            }
            // 编辑员工
            $data = [
                'name' => input('post.name'),
                'tel'  => input('post.tel'),
                'position' => input('post.position')
            ];
            $res = StorePersonnelModel::where([
                        ['id','<>',$id],
                        ['tel','=',$data['tel']]
                    ])
                    ->find();
            if ($res){
                return $this->responseError('手机号码已存在');
            }
            $res = StorePersonnelModel::where('id',$id)->update($data);
            if ($res !== false){
                return $this->responseSuccess('编辑成功');
            }
            return $this->responseError('编辑失败');
        }
    }

    /**
     * 删除员工
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = StorePersonnelModel::where('id',$id)->delete();
        if ($res){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('删除失败');
    }
}
