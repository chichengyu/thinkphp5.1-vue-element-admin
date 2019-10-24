<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\lib\Auth;
use app\lib\exception\LoginException;
use app\lib\exception\RoleException;
use app\lib\Token;
use think\facade\Request;

class Base extends BaseController{
    protected $userInfo;

   protected function initialize()
    {
        parent::initialize();
        $module = Request::module();
        $controller = Request::controller();
        $action = Request::action();
        $url = $module.'/'.ltrim(strchr($controller,'.'),'.').'/'.$action;
        if (in_array(strtolower($url),config('routes.pay'))){
            return true;
        }
        $token = Request::instance()->header('token');
        if (!$token){
            throw new LoginException();
        }
        $userInfo = session(Token::getInstance()->getAuthDecodeToken($token));
        if (!$userInfo){
            throw new LoginException(['msg'=>'请先登陆']);
        }
        $cacheToken = cache('SID'.$userInfo->id);
        if($cacheToken && $cacheToken != session_id()){
            session(null);
            throw new LoginException(['msg'=>'账号异地登陆，你已被迫下线']);
        }
        $this->userInfo = $userInfo;
        if ($userInfo->id == 1){
            return true;
        }
        $auth = new Auth();
        $user = $auth->getUserInfo($userInfo['id']);
        if (!empty($user)){
            if ($userInfo->group_id != $user['group_id']){
                session(null);
                throw new LoginException(['msg'=>'请重新登陆']);
            }
            if ($user['status'] == 0){
                session(null);
                throw new LoginException(['msg'=>'账号已禁用，请联系管理员']);
            }
        }
        if (!in_array(strtolower($url),config('routes.action')) && !$auth->check($url,$userInfo['id'])){
            throw new RoleException();
        }
    }
}