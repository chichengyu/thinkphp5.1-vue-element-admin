<?php
namespace app\api\controller;

use think\Controller;
use app\lib\ResponseJson;

class BaseController extends Controller{
    use ResponseJson;

    /**
     * @param int $code    // 返回的状态吗
     * @param string $msg  // 返回的提示信息
     * @param bool $flag   // false表示请求失败，并返回失败的信息
     * @param string $data // 返回的数据
     * @return \think\response\Json
     */
    public function res_json($msg='', $code=0, $flag=false, $data=''){
        $obj = [
            'code'   => $code,
            'msg'    => $msg,
            'url'    => request()->url(true)
        ];
        if ($flag){
            $obj['data'] = $data;
        }
        return json($obj);
    }
}