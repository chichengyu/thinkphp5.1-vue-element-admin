<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\lib\Token;
use think\Request;
use app\api\model\StorePersonnel as StorePersonnelModel;
use app\api\model\StoreAuthGroup as AuthGroupModel;

class Login extends BaseController {

    /**  登陆
     * @param Request $request
     * @return \think\response\Json
     */
    public function login(Request $request)
    {
        if ($request->isPost()){
            $username = $request->post('username');
            $password = $request->post('password');
            $user = StorePersonnelModel::where('tel',$username)->find();
            if (!$user){
                return $this->responseError('账号不存在');
            }
            if (!password_verify($password,$user->password)){
                return $this->responseError('密码错误');
            }
            if ($user){
                $data = $this->setLoginData($user->hidden(['password','create_time']));
                unset($data['id']);
                return $this->responseSuccess('登录成功',$data);
            }
            return $this->responseError('登录失败');
        }
    }

    /**
     * 退出登陆
     */
    public function logout(){
        session(null);
        return $this->responseSuccess('退出登录');
    }


    /**  设置登陆数据
     * @param $user
     * @return \think\response\Json
     */
    protected function setLoginData($user){
        $instance = Token::getInstance();
        $token = $instance->generateToken($user->id);
        $data = [
            'token' => $instance->getAuthEncodeToken($token),
            'id' => $user->id,
            'name' => $user->name,
        ];
        if (isset($user->group_id)){
            if ($user->group_id > 0){
                $group = AuthGroupModel::where('id',$user->group_id)->find();
                if (!$group){
                    return $this->res_json('登录失败');
                }
            }
            session('group_id',$user->group_id);
        }
        if ($user->id != 1 && $group->id){
            $data['roles'] = $group->title;
            $data['rules'] = explode(',',$group->rules);
        }else{
            $data['roles'] = 1;
            $data['rules'] = [];
        }
//        session($token,$data);
        session($token,$user);
//        session('uid',$user->id);
//        session('store_id',$user->store_id);
//        session('is_store_status',$user->status);
        return $data;
    }
}