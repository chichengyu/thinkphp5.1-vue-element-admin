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

        $token = Request::instance()->header('token');
        if (!$token){
            throw new LoginException();
        }
        $userInfo = session(Token::getInstance()->getAuthDecodeToken($token));
        if (!$userInfo){
            throw new LoginException(['msg'=>'请先登陆']);
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
            if ($user['user_status'] != 1){
                session(null);
                throw new LoginException(['msg'=>'账号已禁用，请联系管理员']);
            }
        }
        if (!in_array(strtolower($url),config('routes.action')) && !$auth->check($url,$userInfo['id'])){
            throw new RoleException();
        }
    }


    /** 上传一张图片
     * @param $file     文件对象
     * @param $dirPath  保存路径
     * @return array
     */
    public function imageUpload($file,$dirPath){
        $path = $_SERVER['DOCUMENT_ROOT'].'/';
        $subPath = 'static/uploads/'.$dirPath.'/'.date('Y-m');
        is_dir($path.$subPath) || mkdir($path.$subPath,0777,true);
        $info = $file->validate(['size'=>1024*1024*2,'ext'=>'jpg,png,gif'])->rule('uniqid')->move($path.$subPath);
        if ($info){
            return ['code' => 1,'msg' => '上传成功','path'=>'/'.$subPath.'/'.$info->getFilename(),'ivew_path' => httpHost().$_SERVER['HTTP_HOST'].'/'.$subPath.'/'.$info->getFilename()];
        }
        return ['code' => 0,'msg' =>'上传失败' . $file->getError()];
    }

    /** 删除图片
     * @param $path  图片路径
     * @return bool
     */
    public function delImage($path){
        try{
            @unlink('.'.$path);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}