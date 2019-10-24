<?php
namespace app\api\controller\v1;

use app\api\validate\AdministratorsValidate;
use app\api\validate\IDMustValidate;
use think\Request;
use think\Db;
use app\api\model\Administrators as AdministratorsModel;
use app\api\model\AuthGroup as AuthGroupModel;
use app\api\model\AuthGroupAccess as AuthGroupAccessModel;

class Administrators extends Base{

    /**
     * 添加
     */
    public function add(Request $request){
        if ($request->isPost()){
            (new AdministratorsValidate)->goCheck();
            $data = input('post.');
            $data['password'] = password_hash(md5('123456789'),PASSWORD_DEFAULT);
            $data['create_time'] = date('Y-m-d H:i:s');
            $numberuser = new AdministratorsModel;
            Db::startTrans();
            try{
                if ($numberuser->allowField(true)->save($data)){
                    $res = Db::name('auth_group_access')->insert(['uid' => $numberuser->id, 'group_id' => $data['group_id'],]);
                    if ($res){
                        Db::commit();
                        return $this->responseSuccess('添加成功');
                    }
                    Db::rollback();
                }
                return $this->responseError('添加失败');
            }catch (\Exception $e){
                Db::rollback();
                return $this->responseError('添加失败');
            }
        }
    }

    /**
     * 列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $keywords = input('get.keywords');
        $where = [['is_del','=',0]];
        if (isset($keywords)){
            $where[] = ['tel','like',["%$keywords%"]];
        }
        $data = AdministratorsModel::pageAdminUser($page,$limit,$where);
        $data['groups'] = AuthGroupModel::select();
        if ($data){
            return $this->responseSuccess('success',$data);
        }
        return $this->responseError('error');
    }

    /**
     * 编辑
     */
    public function edit(Request $request,$id){
        if($request->isPost()){
            (new IDMustValidate)->goCheck();
            (new AdministratorsValidate)->goCheck();
            $data = input('post.');
            $data = [
                'tel' => $data['tel'],
                'name' => $data['name'],
                'group_id' => $data['group_id'],
                'address_id' => $data['address_id'],
                'store_name' => $data['store_name'],
                'status' => $data['status'],
            ];
            $res = AdministratorsModel::get($id);
            Db::startTrans();
            try{
                if ($res){
                    $rs = AuthGroupAccessModel::where('uid',$id)->setField('group_id',$data['group_id']);
                    if ($res->save($data) && $rs !== false){
                        Db::commit();
                        return $this->responseSuccess('编辑成功');
                    }
                }
                Db::rollback();
                return $this->responseError('编辑失败');
            }catch (\Exception $e){
                Db::rollback();
                return $this->responseError('编辑失败');
            }
        }
    }

    /**
     * 重置密码
     */
    public function resetPassword(Request $request,$id){
        if($request->isGet()){
            (new IDMustValidate)->goCheck();
            $res = AdministratorsModel::where('id',$id)->setField('password',password_hash(md5('123456789'),PASSWORD_DEFAULT));
            if ($res !== false){
                return $this->responseSuccess('重置成功');
            }
            return $this->responseError('重置失败');
        }
    }

    /**
     * 删除
     */
    public function del($id){
        (new IDMustValidate)->goCheck();
        $res = AdministratorsModel::where('id',$id)->setField('is_del',1);
        if ($res !== false){
            return $this->responseSuccess('删除成功');
        }
        return $this->responseError('删除失败');
    }

    /**
     * 个人资料
     */
    public function getPersonal(){
        try{
            $personal = AdministratorsModel::where('id',$this->userInfo->id)->find()->hidden(['password']);
            if ($personal){
                $personal->group_title = '无';
                if ($personal->group_id != 0){
                    $group = AuthGroupModel::field('title')->where('id',$personal->group_id)->find();
                    if ($group) $personal->group_title = $group->title;
                }
                return $this->responseSuccess('success',$personal);
            }
            return $this->responseError('error');
        }catch (\Exception $e){
            return $this->responseError('error');
        }
    }

    /**
     * 密码修改
     */
    public function editPassword(Request $request){
        if($request->isPost()){
            $data = input('post.');
            try{
                $user = AdministratorsModel::get($this->userInfo->id);
                if (!password_verify($data['password'],$user->password)){
                    return $this->responseError('旧密码不正确');
                }
                $password = password_hash($data['new_password'],PASSWORD_DEFAULT);
                if ($password == $user->password){
                    return $this->responseError('旧密码与新密码不能一致');
                }
                $res = AdministratorsModel::where('id',$this->userInfo->id)->setField('password',$password);
                if ($res !== false){
                    return $this->responseSuccess('修改成功');
                }
                return $this->responseError('修改失败');
            }catch(\Exception $e){
                return $this->responseError('修改失败');
            }
        }
    }
}