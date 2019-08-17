<?php 
namespace app\lib\exception;

class ParamsException extends BaseException
{
    protected $code = 200;
    protected $msg = 'Params Error';
    protected $errorCode = 400;
}