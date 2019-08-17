<?php
namespace app\api\model;

class CarCountry extends BaseModel{

    protected $field = ['id','name','create_time'];

    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;
}