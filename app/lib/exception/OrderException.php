<?php 
namespace app\lib\exception;

class OrderException extends BaseException
{
    protected $code = 200;
    protected $msg = '下单失败！';
    protected $errorCode = 0;
}