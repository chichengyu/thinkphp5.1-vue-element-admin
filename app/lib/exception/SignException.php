<?php 
namespace app\lib\exception;

class SignException extends BaseException
{
    protected $code = 200;
    protected $msg = '非法请求';
    protected $errorCode = 401;// （禁止） 服务器拒绝请求
}