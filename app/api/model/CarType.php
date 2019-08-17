<?php
namespace app\api\model;

class CarType extends BaseModel{

    protected $field = ['id','name','create_time'];

    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;


    public function CarDetails(){
        return $this->hasMany(CarDetails::class,'cartype_id','id');
    }
}