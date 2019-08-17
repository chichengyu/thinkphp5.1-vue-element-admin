<?php 
namespace app\lib\exception;

class RoleException extends BaseException
{
    protected $code = 200;
    protected $msg = '没有权限';
    protected $errorCode = 401;// 无权请求
}