<?php 
namespace app\lib\exception;

class LoginException extends BaseException
{
    protected $code = 200;
    protected $msg = '非法登陆';
    protected $errorCode = 0;// （禁止） 服务器拒绝请求
}